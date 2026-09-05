<?php

namespace App\Services;

use App\Models\Departure;
use App\Models\TravelPackage;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DepartureService
{
    public function create(
        TravelPackage $travelPackage,
        array $data
    ): Departure {
        return Departure::create([
            'code' => $data['code'] ?? $this->generateCode(),
            'travel_package_id' => $travelPackage->id,
            'name' => $data['name'],
            'departure_date' => $data['departure_date'],
            'quota' => $data['quota'],
            'estimated_price' => $data['estimated_price']
                ?? $travelPackage->estimated_price,
            'status' => 'draft',
            'notes' => $data['notes'] ?? null,
        ]);
    }

    /**
     * Menghapus / Mengeluarkan Grup dari Jadwal Keberangkatan
     */
    public function removeGroup(Departure $departure, $groupId): void
    {
        // Cari grup berdasarkan ID dan pastikan grup tersebut milik jadwal ini
        $group = $departure->groups()->findOrFail($groupId);

        // Lepaskan kaitan grup dari jadwal (set departure_id jadi null)
        $group->update(['departure_id' => null]);
        
        // Catatan: Status jamaah di dalam grup tersebut tidak perlu diubah, 
        // karena mereka kembali berstatus 'nganggur' dan siap ditarik ke jadwal lain.
    }

    /**
     * Menghapus Jadwal Keberangkatan
     */
    public function delete(Departure $departure): void
    {
        // Proteksi: Jadwal yang sudah berangkat tidak boleh dihapus
        if (in_array($departure->status, ['departed', 'completed'])) {
            throw new \RuntimeException('Jadwal yang sedang berjalan atau sudah selesai tidak dapat dihapus.');
        }

        // Lepaskan semua rombongan yang menempel terlebih dahulu secara otomatis
        foreach ($departure->groups as $group) {
            $group->update(['departure_id' => null]);
        }

        // Setelah kosong, baru hapus jadwalnya
        $departure->delete();
    }

    public function open(Departure $departure): Departure
    {
        if ($departure->status !== 'draft') {
            throw new \RuntimeException(
                'Departure hanya bisa dibuka dari status draft.'
            );
        }

        $departure->update([
            'status' => 'open',
        ]);

        return $departure->fresh();
    }

    public function close(Departure $departure): Departure
    {
        if (!in_array(
            $departure->status,
            ['open', 'filling', 'full'],
            true
        )) {
            throw new \RuntimeException(
                'Departure tidak dapat ditutup dari status saat ini.'
            );
        }

        $departure->update([
            'status' => 'closed',
        ]);

        return $departure->fresh();
    }

    public function finalize(Departure $departure): Departure
    {
        $departure->update([
            'status' => 'ready',
        ]);

        return $departure->fresh();
    }

    protected function generateCode(): string
    {
        return 'DEP-' . now()->format('YmdHis');
    }

    /**
     * Mengeksekusi keberangkatan rombongan
     */
    public function markAsDeparted(Departure $departure): Departure
    {
        // Pastikan relasi ter-load
        $departure->loadMissing('groups.activeMemberships.enrollment');

        DB::transaction(function () use ($departure) {
            // 1. Ubah status jadwal utama
            $departure->update(['status' => 'departed']);

            // 2. Loop setiap grup/rombongan di dalam jadwal ini
            foreach ($departure->groups as $group) {
                $group->update(['status' => 'departed']);

                // 3. Ubah status semua jamaah di dalam grup tersebut
                foreach ($group->activeMemberships as $membership) {
                    $membership->enrollment->update([
                        'status' => 'departed'
                    ]);
                }
            }
        });

        return $departure->fresh();
    }

    /**
     * Menyelesaikan seluruh rangkaian perjalanan ibadah
     */
    public function markAsCompleted(Departure $departure): Departure
    {
        $departure->loadMissing('groups.activeMemberships.enrollment');

        DB::transaction(function () use ($departure) {
            // 1. Ubah status jadwal utama jadi selesai
            $departure->update(['status' => 'completed']);

            // 2. Loop setiap grup/rombongan
            foreach ($departure->groups as $group) {
                $group->update(['status' => 'completed']);

                // 3. Ubah status semua jamaah jadi selesai
                foreach ($group->activeMemberships as $membership) {
                    $membership->enrollment->update([
                        'status' => 'completed'
                    ]);
                }
            }
        });

        return $departure->fresh();
    }

    public function autoCompleteFinishedDepartures(): array
    {
        // Ambil semua jadwal yang statusnya sedang 'departed' (Sedang Umroh)
        $departures = Departure::where('status', 'departed')
                        ->with(['travelPackage', 'groups.activeMemberships.enrollment'])
                        ->get();

        $completedList = [];

        foreach ($departures as $departure) {
            // Ambil durasi paket (misal: 9 hari)
            $duration = $departure->travelPackage->duration_days ?? 9; 
            
            // Hitung tanggal pulang (Tanggal Berangkat + Durasi Hari)
            $returnDate = Carbon::parse($departure->departure_date)->addDays($duration);

            // Jika hari ini sudah melewati atau sama dengan tanggal pulang
            if (now()->startOfDay()->greaterThanOrEqualTo($returnDate->startOfDay())) {
                
                // Gunakan transaksi agar aman
                DB::transaction(function () use ($departure) {
                    $departure->update(['status' => 'completed']);
                    
                    foreach ($departure->groups as $group) {
                        $group->update(['status' => 'completed']);
                        
                        foreach ($group->activeMemberships as $membership) {
                            $membership->enrollment->update(['status' => 'completed']);
                        }
                    }
                });

                // Simpan nama jadwal yang sukses diupdate untuk dilaporkan
                $completedList[] = "{$departure->name} ({$departure->code})";
            }
        }

        return $completedList;
    }
}