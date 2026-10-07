<?php

namespace App\Services;

use App\Models\Departure;
use App\Models\TravelPackage;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DepartureService
{
    public function create(TravelPackage $travelPackage, array $data): Departure
    {
        return Departure::create([
            'code' => $data['code'] ?? $this->generateCode(),
            'travel_package_id' => $travelPackage->id,
            'name' => $data['name'],
            'departure_date' => $data['departure_date'],
            'quota' => $data['quota'],
            'estimated_price' => $data['estimated_price'] ?? $travelPackage->estimated_price,
            'status' => 'draft',
            'notes' => $data['notes'] ?? null,
        ]);
    }

    public function removeGroup(Departure $departure, $groupId): void
    {
        DB::transaction(function () use ($departure, $groupId) {
            $group = $departure->groups()->with('activeMemberships.enrollment.paymentPlan')->findOrFail($groupId);

            $group->update(['departure_id' => null]);

            // 🔒 SINKRONISASI: Kembalikan status jamaah ke nganggur & hapus harga final
            foreach ($group->activeMemberships as $membership) {
                $enrollment = $membership->enrollment;
                $enrollment->update([
                    'status' => 'waiting_schedule',
                    'scheduled_at' => null,
                    'final_price' => null,
                ]);

                if ($enrollment->paymentPlan) {
                    $enrollment->paymentPlan->update(['final_target_amount' => null]);
                    app(PaymentService::class)->refreshEnrollmentStatus($enrollment);
                }
            }
        });
    }

    public function delete(Departure $departure): void
    {
        if (in_array($departure->status, ['departed', 'completed'])) {
            throw new \RuntimeException('Jadwal yang berjalan/selesai tidak dapat dihapus.');
        }

        DB::transaction(function () use ($departure) {
            // 🔒 SINKRONISASI: Lepas kaitan dan reset status seluruh jamaah di dalamnya
            foreach ($departure->groups()->with('activeMemberships.enrollment.paymentPlan')->get() as $group) {
                $group->update(['departure_id' => null]);

                foreach ($group->activeMemberships as $membership) {
                    $enrollment = $membership->enrollment;
                    $enrollment->update([
                        'status' => 'waiting_schedule',
                        'scheduled_at' => null,
                        'final_price' => null,
                    ]);

                    if ($enrollment->paymentPlan) {
                        $enrollment->paymentPlan->update(['final_target_amount' => null]);
                        app(PaymentService::class)->refreshEnrollmentStatus($enrollment);
                    }
                }
            }
            $departure->delete();
        });
    }

    public function open(Departure $departure): Departure
    {
        if ($departure->status !== 'draft') {
            throw new \RuntimeException('Departure hanya bisa dibuka dari status draft.');
        }
        $departure->update(['status' => 'open']);

        return $departure->fresh();
    }

    public function close(Departure $departure): Departure
    {
        if (! in_array($departure->status, ['open', 'filling', 'full'], true)) {
            throw new \RuntimeException('Departure tidak dapat ditutup dari status saat ini.');
        }
        $departure->update(['status' => 'closed']);

        return $departure->fresh();
    }

    public function finalize(Departure $departure): Departure
    {
        // 🔒 EXPLICIT STATE TRANSITION: Hanya dari status aktif/penuh/ditutup
        $allowedOriginStates = ['open', 'filling', 'full', 'closed'];

        if (! in_array($departure->status, $allowedOriginStates, true)) {
            throw new \RuntimeException("Validasi Gagal: Jadwal dengan status '{$departure->status}' tidak dapat difinalisasi.");
        }

        $departure->update(['status' => 'ready']);

        return $departure->fresh();
    }

    protected function generateCode(): string
    {
        return 'DEP-'.now()->format('YmdHis');
    }

    public function markAsDeparted(Departure $departure): Departure
    {
        // 🔒 STATE TRANSITION WORKFLOW: Cegah Draft/Open langsung terbang
        if (! in_array($departure->status, ['ready', 'closed', 'full'], true)) {
            throw new \RuntimeException('Validasi Gagal: Hanya jadwal yang sudah ditutup/siap (Ready/Closed/Full) yang bisa diberangkatkan.');
        }

        $departure->loadMissing('groups.activeMemberships.enrollment');

        DB::transaction(function () use ($departure) {
            $departure->update(['status' => 'departed']);

            foreach ($departure->groups as $group) {
                $group->update(['status' => 'departed']);
                foreach ($group->activeMemberships as $membership) {
                    $membership->enrollment->update(['status' => 'departed']);
                }
            }
        });

        return $departure->fresh();
    }

    public function markAsCompleted(Departure $departure): Departure
    {
        // 🔒 STATE TRANSITION WORKFLOW: Cegah jadwal yang belum berangkat tiba-tiba selesai
        if ($departure->status !== 'departed') {
            throw new \RuntimeException('Validasi Gagal: Hanya jadwal berstatus "Departed" (Sedang Berangkat) yang bisa diselesaikan.');
        }

        $departure->loadMissing('groups.activeMemberships.enrollment');

        DB::transaction(function () use ($departure) {
            $departure->update(['status' => 'completed']);

            foreach ($departure->groups as $group) {
                $group->update(['status' => 'completed']);
                foreach ($group->activeMemberships as $membership) {
                    $membership->enrollment->update(['status' => 'completed']);
                }
            }
        });

        return $departure->fresh();
    }

    public function autoCompleteFinishedDepartures(): array
    {
        $departures = Departure::where('status', 'departed')
            ->with(['travelPackage', 'groups.activeMemberships.enrollment'])
            ->get();

        $completedList = [];

        foreach ($departures as $departure) {
            $duration = $departure->travelPackage->duration_days ?? 9;
            $returnDate = Carbon::parse($departure->departure_date)->addDays($duration);

            if (now()->startOfDay()->greaterThanOrEqualTo($returnDate->startOfDay())) {
                DB::transaction(function () use ($departure) {
                    $departure->update(['status' => 'completed']);

                    foreach ($departure->groups as $group) {
                        $group->update(['status' => 'completed']);
                        foreach ($group->activeMemberships as $membership) {
                            $membership->enrollment->update(['status' => 'completed']);
                        }
                    }
                });
                $completedList[] = "{$departure->name} ({$departure->code})";
            }
        }

        return $completedList;
    }
}
