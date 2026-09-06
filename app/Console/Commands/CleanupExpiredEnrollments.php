<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Enrollment;

class CleanupExpiredEnrollments extends Command
{
    // Nama perintah yang akan dipanggil
    protected $signature = 'enrollments:cleanup';

    // Deskripsi perintah
    protected $description = 'Menghapus pendaftaran pending yang belum dibayar lebih dari 1x24 jam';

    public function handle()
    {
        $this->info('Mulai membersihkan data pendaftaran kadaluarsa...');

        $sampahPendaftaran = Enrollment::where('status', 'enrolled')
            ->where('created_at', '<', now()->subHours(24))
            ->get();

        $count = 0;
        foreach ($sampahPendaftaran as $enrollment) {
            if ($enrollment->paymentPlan) {
                $enrollment->paymentPlan->delete();
            }
            $enrollment->delete();
            $count++;
        }

        $this->info("Pembersihan selesai! Total {$count} pendaftaran sampah berhasil dihapus.");
    }
}