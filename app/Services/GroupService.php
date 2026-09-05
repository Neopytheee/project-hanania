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

        if ($departure && $data['capacity'] > $departure->quota) {
            throw new \InvalidArgumentException('Kapasitas group tidak boleh melebihi quota departure.');
        }

        return Group::create([
            'departure_id' => $departure?->id, // Bisa null
            'code'         => $data['code'],
            'name'         => $data['name'],
            'capacity'     => $data['capacity'],
            'status'       => 'draft',
        ]);
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
            if (
                !in_array(
                    $enrollment->status,
                    [
                        'funds_sufficient',
                        'waiting_schedule',
                    ],
                    true
                )
            ) {
                throw new \RuntimeException(
                    'Jamaah belum siap untuk dijadwalkan.'
                );
            }

            if ($group->remainingCapacity() <= 0) {
                throw new \RuntimeException(
                    'Rombongan sudah penuh.'
                );
            }

            $existingActive = $enrollment
                ->groupMemberships()
                ->where('status', 'active')
                ->exists();

            if ($existingActive) {
                throw new \RuntimeException(
                    'Jamaah sudah memiliki rombongan aktif.'
                );
            }

            $membership = GroupMembership::create([
                'group_id'      => $group->id,
                'enrollment_id' => $enrollment->id,
                'status'        => 'active',
                'joined_at'     => now(),
                'assigned_by'   => $admin->id,
            ]);

            // ==========================================
            // LOGIKA BARU: UPDATE STATUS & HARGA
            // ==========================================
            if ($group->departure_id) {
                // Jika SUDAH punya jadwal: Kunci harga dan set status scheduled
                $hargaFinal = $group->departure->estimated_price;
                
                $enrollment->update([
                    'status'       => 'scheduled',
                    'scheduled_at' => now(),
                    'final_price'  => $hargaFinal,
                ]);

                if ($enrollment->paymentPlan && $hargaFinal > 0) {
                    $enrollment->paymentPlan->update([
                        'final_target_amount' => $hargaFinal
                    ]);
                    app(PaymentService::class)->refreshEnrollmentStatus($enrollment);
                }
            } else {
                // Jika BELUM punya jadwal: Status tetap waiting_schedule
                $enrollment->update([
                    'status' => 'waiting_schedule'
                ]);
            }
            // ==========================================

            $this->refreshGroupStatus($group);

            return $membership->fresh([
                'group',
                'enrollment',
            ]);
        });
    }

    public function removeEnrollment(
        GroupMembership $membership,
        User $admin,
        string $reason
    ): GroupMembership {
        return DB::transaction(function () use (
            $membership,
            $admin,
            $reason
        ) {
            $membership->update([
                'status'  => 'removed',
                'left_at' => now(),
                'reason'  => $reason,
            ]);

            $enrollment = $membership->enrollment;

            /*
             * Jika belum berangkat dan belum dibatalkan,
             * kembali menunggu penjadwalan.
             */
            if (!in_array(
                $enrollment->status,
                ['cancelled', 'departed', 'completed'],
                true
            )) {
                $enrollment->update([
                    'status' => 'waiting_schedule',
                ]);
            }

            $this->refreshGroupStatus(
                $membership->group
            );

            return $membership->fresh();
        });
    }

    protected function refreshGroupStatus(
        Group $group
    ): void {
        $activeMembers = $group
            ->activeMemberships()
            ->count();

        if ($activeMembers === 0) {
            $status = 'draft';
        } elseif ($activeMembers >= $group->capacity) {
            $status = 'full';
        } else {
            $status = 'filling';
        }

        $group->update([
            'status' => $status,
        ]);
    }

    public function assignToDeparture(Group $group, Departure $departure): Group
    {
        if ($group->capacity > $departure->quota) {
            throw new \RuntimeException('Kapasitas rombongan melebihi sisa kuota jadwal.');
        }

        return DB::transaction(function () use ($group, $departure) {
            // 1. Hubungkan grup ke jadwal
            $group->update(['departure_id' => $departure->id]);

            // 2. Ambil estimasi harga dari jadwal terbaru
            $hargaJadwal = $departure->estimated_price;

            // 3. Update semua jamaah di dalam grup ini
            foreach ($group->activeMemberships as $membership) {
                $enrollment = $membership->enrollment;
                
                $enrollment->update([
                    'status'       => 'scheduled',
                    'scheduled_at' => now(),
                    'final_price'  => $hargaJadwal, // Update harga sesuai jadwal
                ]);

                if ($enrollment->paymentPlan) {
                    $enrollment->paymentPlan->update([
                        'final_target_amount' => $hargaJadwal
                    ]);
                    
                    // Cek otomatis apakah jamaah jadi kurang bayar/lunas
                    app(PaymentService::class)->refreshEnrollmentStatus($enrollment);
                }
            }

            $this->refreshGroupStatus($group);
            return $group->fresh(['departure', 'activeMemberships']);
        });
    }
}