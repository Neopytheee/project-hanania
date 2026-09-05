<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\DepartureService; // 🪄 WAJIB PANGGIL KOKI-NYA

class CompleteFinishedDepartures extends Command
{
    /**
     * Nama perintah untuk dipanggil di terminal
     */
    protected $signature = 'departures:complete';

    /**
     * Deskripsi tugas robot
     */
    protected $description = 'Otomatis menyelesaikan status keberangkatan yang durasi paketnya sudah habis';

    /**
     * Otak dari robot (Kini Super Clean, cuma nyuruh Service!)
     */
    public function handle(DepartureService $departureService)
    {
        $this->info('Mencari rombongan yang jadwal pulangnya hari ini...');

        // 1. Robot menyuruh Koki (Service) untuk mengeksekusi Database
        $completedList = $departureService->autoCompleteFinishedDepartures();

        // 2. Robot melaporkan hasil kerjaan Koki ke layar Terminal
        foreach ($completedList as $departureInfo) {
            $this->info("✅ Jadwal {$departureInfo} otomatis diselesaikan.");
        }

        $count = count($completedList);
        $this->info("Eksekusi selesai! Total ada {$count} jadwal yang di-update.");
    }
}