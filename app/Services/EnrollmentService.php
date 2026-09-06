<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Enrollment;
use App\Models\PaymentPlan;
use App\Models\TravelPackage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Exception; // Tambahkan ini untuk melempar error validasi

class EnrollmentService
{
    public function create(Customer $customer, TravelPackage $travelPackage, array $data = []): Enrollment
    {
        // ==========================================
        // 🛡️ LAPIS 1: VALIDASI ANTI-SPAM (Maks. 2 Pending)
        // ==========================================
        $pendingCount = Enrollment::where('customer_id', $customer->id)
            ->whereIn('status', ['enrolled', 'payment_due'])
            ->count();

        if ($pendingCount >= 2) {
            throw new Exception('Selesaikan dulu pembayaran setoran awal pada pendaftaran Anda sebelumnya sebelum memilih paket baru ya!');
        }

        // Tentukan data penumpang
        $relationship = $data['relationship'] ?? 'Diri Sendiri';
        $passengerName = ($relationship === 'Diri Sendiri') 
            ? $customer->name 
            : ($data['passenger_name'] ?? $customer->name);

        // ==========================================
        // 🛡️ LAPIS 2: VALIDASI ANTI-GANDA
        // ==========================================
        $isDuplicate = Enrollment::where('customer_id', $customer->id)
            ->where('travel_package_id', $travelPackage->id)
            ->where('passenger_name', $passengerName)
            ->whereNotIn('status', ['cancelled', 'completed'])
            ->exists();

        if ($isDuplicate) {
            throw new Exception('Nama jamaah ini sudah terdaftar di paket tersebut. Silakan pilih paket lain atau cek daftar tabungan Anda.');
        }

        // ==========================================
        // 💾 PROSES SIMPAN DATABASE
        // ==========================================
        return DB::transaction(function () use ($customer, $travelPackage, $passengerName, $relationship) {
            
            // 1. Buat Enrollment (Pendaftaran)
            $enrollment = Enrollment::create([
                'enrollment_number'        => $this->generateEnrollmentNumber($travelPackage),
                'customer_id'              => $customer->id,
                'passenger_name'           => $passengerName, 
                'relationship'             => $relationship,  
                'travel_package_id'        => $travelPackage->id,
                'unique_code'              => random_int(100,999),
                'estimated_price_snapshot' => $travelPackage->estimated_price,
                'status'                   => 'enrolled', 
            ]);

            // 2. Buat Payment Plan (Buku Tabungan Utama)
            PaymentPlan::create([
                'enrollment_id'           => $enrollment->id,
                'estimated_target_amount' => $travelPackage->estimated_price,
                'minimum_initial_payment' => 1000000, 
                'minimum_monthly_payment' => 500000,
                'status'                  => 'active',
            ]);

            return $enrollment;
        });
    }

    /**
     * PEMBERSIH OTOMATIS: Hapus tagihan Midtrans pending yang > 5 menit
     */
    public function cleanupExpiredTransactions(Enrollment $enrollment): void
    {
        if ($enrollment->paymentPlan) {
            $enrollment->paymentPlan->transactions()
                ->where('status', 'pending')
                ->whereNotNull('snap_token')
                ->where('created_at', '<', now()->subMinutes(5))
                ->delete();
        }
    }

    protected function generateEnrollmentNumber(TravelPackage $travelPackage): string
    {
        $prefix = ($travelPackage->category === 'HAJI') ? 'HJI' : 'UMR';

        do {
            $number = $prefix . '-' . now()->format('Ym') . '-' . random_int(1000, 9999);
        } while (Enrollment::where('enrollment_number', $number)->exists());

        return $number;
    }

    /**
     * Menghitung progres tabungan (Persentase, Total Bayar, Sisa)
     */
    public function calculateProgress(Enrollment $enrollment): array
    {
        $totalHarga = $enrollment->final_price ?? $enrollment->estimated_price_snapshot ?? 0;
        
        $totalDibayar = 0;
        if ($enrollment->paymentPlan) {
            $totalDibayar = (float) $enrollment->paymentPlan->transactions()
                                        ->where('status', 'verified')
                                        ->sum('amount');
        }
        
        $sisaTagihan = max(0, $totalHarga - $totalDibayar);
        $persentase = 0;

        if ($totalHarga > 0) {
            $persentase = ($totalDibayar / $totalHarga) * 100;
            $persentase = $persentase > 100 ? 100 : round($persentase, 1);
        }

        return [
            'total_harga'   => $totalHarga,
            'total_dibayar' => $totalDibayar,
            'sisa_tagihan'  => $sisaTagihan,
            'persentase'    => $persentase,
        ];
    }
}