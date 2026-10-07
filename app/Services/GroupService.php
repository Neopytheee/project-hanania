<?php

namespace App\Services;

use App\Models\Departure;
use App\Models\Enrollment;
use App\Models\Group;
use App\Models\GroupMembership;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class GroupService
{
    public function create(array $data, ?Departure $departure = null): Group
    {
        if ($data['capacity'] <= 0) {
            throw new \InvalidArgumentException('Kapasitas group harus lebih dari 0.');
        }

        return DB::transaction(function () use ($data, $departure) {
            if ($departure) {
                // 🔒 Row Lock & Cek Total Kuota Seluruh Grup
                $lockedDeparture = Departure::where('id', $departure->id)->lockForUpdate()->first();
                $existingCapacity = $lockedDeparture->groups()->sum('capacity');

                if (($existingCapacity + $data['capacity']) > $lockedDeparture->quota) {
                    throw new \InvalidArgumentException('Total kapasitas grup melebihi sisa quota departure.');
                }
            }

            return Group::create([
                'departure_id' => $departure?->id,
                'code' => $data['code'],
                'name' => $data['name'],
                'capacity' => $data['capacity'],
                'status' => 'draft',
            ]);
        });
    }

    public function assignEnrollment(
        Group $group,
        Enrollment $enrollment,
        User $admin
    ): GroupMembership {
        return DB::transaction(function () use (
            $group,
            $enrollment,
            $admin
        ) {
            // 🔒 1. LOCK GRUP: Kunci data grup agar kapasitas tidak jebol
            $lockedGroup = Group::where('id', $group->id)->lockForUpdate()->first();

            // 🔒 2. LOCK ENROLLMENT: Kunci data pendaftar agar tidak dimasukkan ke 2 grup bersamaan
            $lockedEnrollment = Enrollment::where('id', $enrollment->id)->lockForUpdate()->first();

            if (! in_array($lockedEnrollment->status, ['funds_sufficient', 'waiting_schedule'], true)) {
                throw new \RuntimeException('Jamaah belum siap untuk dijadwalkan.');
            }

            if ($lockedGroup->remainingCapacity() <= 0) {
                throw new \RuntimeException('Rombongan sudah penuh.');
            }

            // Gunakan enrollment yang dilock untuk cek membership
            $existingActive = $lockedEnrollment->groupMemberships()
                ->where('status', 'active')
                ->exists();

            if ($existingActive) {
                throw new \RuntimeException('Jamaah sudah memiliki rombongan aktif.');
            }

            $membership = GroupMembership::create([
                'group_id' => $lockedGroup->id,
                'enrollment_id' => $lockedEnrollment->id,
                'status' => 'active',
                'joined_at' => now(),
                'assigned_by' => $admin->id,
            ]);

            // ==========================================
            // LOGIKA UPDATE STATUS & HARGA
            // ==========================================
            if ($lockedGroup->departure_id) {
                $hargaFinal = $lockedGroup->departure->estimated_price;

                $lockedEnrollment->update([
                    'status' => 'scheduled',
                    'scheduled_at' => now(),
                    'final_price' => $hargaFinal,
                ]);

                if ($lockedEnrollment->paymentPlan && $hargaFinal > 0) {
                    $lockedEnrollment->paymentPlan->update([
                        'final_target_amount' => $hargaFinal,
                    ]);
                    app(PaymentService::class)->refreshEnrollmentStatus($lockedEnrollment);
                }
            } else {
                $lockedEnrollment->update([
                    'status' => 'waiting_schedule',
                ]);
            }

            $this->refreshGroupStatus($lockedGroup);

            return $membership->fresh(['group', 'enrollment']);
        });
    }

    public function removeEnrollment(GroupMembership $membership, User $admin, string $reason): GroupMembership
    {
        return DB::transaction(function () use ($membership, $reason) {
            $membership->update([
                'status' => 'removed',
                'left_at' => now(),
                'reason' => $reason,
            ]);

            $enrollment = $membership->enrollment;

            if (! in_array($enrollment->status, ['cancelled', 'departed', 'completed'], true)) {
                $enrollment->update([
                    'status' => 'waiting_schedule',
                    'scheduled_at' => null,
                    'final_price' => null,
                ]);

                // Reset target tagihan kembali ke estimasi awal
                if ($enrollment->paymentPlan) {
                    $enrollment->paymentPlan->update(['final_target_amount' => null]);
                    app(PaymentService::class)->refreshEnrollmentStatus($enrollment);
                }
            }

            $this->refreshGroupStatus($membership->group);

            return $membership->fresh();
        });
    }

    protected function refreshGroupStatus(Group $group): void
    {
        $activeMembers = $group->activeMemberships()->count();
        $status = $activeMembers === 0 ? 'draft' : ($activeMembers >= $group->capacity ? 'full' : 'filling');
        $group->update(['status' => $status]);
    }

    public function assignToDeparture(Group $group, Departure $departure): Group
    {
        return DB::transaction(function () use ($group, $departure) {
            // 🔒 Row Lock & Cek Total Kuota Seluruh Grup Terkini
            $lockedDeparture = Departure::where('id', $departure->id)->lockForUpdate()->first();
            $existingCapacity = $lockedDeparture->groups()->where('id', '!=', $group->id)->sum('capacity');

            if (($existingCapacity + $group->capacity) > $lockedDeparture->quota) {
                throw new \RuntimeException('Kapasitas rombongan melampaui sisa kuota jadwal keberangkatan.');
            }

            $group->update(['departure_id' => $lockedDeparture->id]);
            $hargaJadwal = $lockedDeparture->estimated_price;

            foreach ($group->activeMemberships as $membership) {
                $enrollment = $membership->enrollment;

                $enrollment->update([
                    'status' => 'scheduled',
                    'scheduled_at' => now(),
                    'final_price' => $hargaJadwal,
                ]);

                if ($enrollment->paymentPlan) {
                    $enrollment->paymentPlan->update(['final_target_amount' => $hargaJadwal]);
                    app(PaymentService::class)->refreshEnrollmentStatus($enrollment);
                }
            }

            $this->refreshGroupStatus($group);

            return $group->fresh(['departure', 'activeMemberships']);
        });
    }
}
