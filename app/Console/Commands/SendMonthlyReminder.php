<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Enrollment;
use App\Notifications\MonthlySavingReminder; // 👈 Panggil file "Surat" tadi di sini

class SendMonthlyReminder extends Command
{
    protected $signature = 'reminders:monthly';
    protected $description = 'Kirim Email & Notif Web pengingat nabung tiap tanggal 25';

    public function handle()
    {
        $this->info('Mulai mengirim pengingat tabungan...');

        // 1. Pak Pos mencari jamaah yang belum lunas
        $activeEnrollments = Enrollment::whereIn('status', ['saving', 'enrolled'])
            ->with(['customer.user', 'travelPackage'])
            ->get();

        $count = 0;
        foreach ($activeEnrollments as $enrollment) {
            $user = $enrollment->customer->user ?? null;

            if ($user) {
                // 2. Pak Pos membagikan "Surat" (Email & Lonceng) ke Jamaah
                $user->notify(new MonthlySavingReminder($enrollment));
                $count++;
            }
        }

        $this->info("Selesai! Berhasil mengirim {$count} notifikasi via Email & Database.");
    }
}