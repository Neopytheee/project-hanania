<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CleanNotifications extends Command
{
    // Nama panggilan robotnya di terminal
    protected $signature = 'notifications:clean';
    protected $description = 'Menghapus notifikasi lama yang sudah dibaca agar database tidak bengkak';

    public function handle()
    {
        $this->info('Mulai menyapu database notifikasi...');

        // Batas waktu: 30 hari ke belakang dari hari ini
        $batasWaktu = \Carbon\Carbon::now()->subDays(30);
        $this->info("Batas waktu hapus: sebelum tanggal " . $batasWaktu->toDateTimeString());

        // Cek jumlah data yang umurnya > 30 hari (TANPA PEDULI SUDAH DIBACA ATAU BELUM)
        $jumlahTarget = \Illuminate\Support\Facades\DB::table('notifications')
            ->where('created_at', '<', $batasWaktu)
            ->count();

        $this->info("Ditemukan {$jumlahTarget} notifikasi lama (baik dibaca maupun belum) untuk dibersihkan.");

        if ($jumlahTarget > 0) {
            $jumlahDihapus = \Illuminate\Support\Facades\DB::table('notifications')
                ->where('created_at', '<', $batasWaktu)
                ->delete();

            $this->info("Berhasil! Tukang sapu telah membuang {$jumlahDihapus} notifikasi kedaluwarsa.");
        } else {
            $this->info("Tidak ada notifikasi yang umurnya lebih dari 30 hari.");
        }
    }
}