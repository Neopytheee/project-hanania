<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Enrollment;
use App\Models\PaymentPlan;
use App\Models\TravelPackage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class EnrollmentService
{
    public function create(Customer $customer, TravelPackage $travelPackage, array $data = []): Enrollment
    {
        return DB::transaction(function () use ($customer, $travelPackage, $data) {
            
            // Tentukan data penumpang (Jika 'Diri Sendiri', otomatis pakai nama dari data customer)
            $relationship = $data['relationship'] ?? 'Diri Sendiri';
            $passengerName = ($relationship === 'Diri Sendiri') 
                ? $customer->name 
                : ($data['passenger_name'] ?? $customer->name);

            // 1. Buat Enrollment (Pendaftaran) beserta info penumpangnya
            $enrollment = Enrollment::create([
                'enrollment_number'        => $this->generateEnrollmentNumber($travelPackage),
                'customer_id'              => $customer->id,
                'passenger_name'           => $passengerName, // <-- Disimpan di sini
                'relationship'             => $relationship,  // <-- Disimpan di sini
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
                ->whereNotNull('snap_token') // Hanya khusus Midtrans
                ->where('created_at', '<', now()->subMinutes(5))
                ->delete();
        }
    }

    protected function generateEnrollmentNumber(TravelPackage $travelPackage): string
    {
        // 💡 LOGIKA IF-ELSE PREFIX
        // Jika kategori HAJI maka awalan 'HJI', selain itu 'UMR' (Umroh)
        $prefix = ($travelPackage->category === 'HAJI') ? 'HJI' : 'UMR';

        do {
            // Hasilnya misal: HJI-202609-3438 atau UMR-202609-3438
            $number = $prefix . '-' . now()->format('Ym') . '-' . random_int(1000, 9999);
            
        } while (Enrollment::where('enrollment_number', $number)->exists());

        return $number;
    }

    /**
     * Menghitung progres tabungan (Persentase, Total Bayar, Sisa)
     */
    public function calculateProgress(Enrollment $enrollment): array
    {
        // 1. Ambil harga paket target:
        // Prioritaskan 'final_price' (jika sudah masuk kloter). 
        // Jika belum, gunakan 'estimated_price_snapshot' saat awal mendaftar.
        $totalHarga = $enrollment->final_price ?? $enrollment->estimated_price_snapshot ?? 0;
        
        // 2. Hitung total dibayar dari transaksi yang "verified"
        $totalDibayar = 0;
        if ($enrollment->paymentPlan) {
            $totalDibayar = (float) $enrollment->paymentPlan->transactions()
                                        ->where('status', 'verified')
                                        ->sum('amount');
        }
        
        // 3. Hitung sisa dan persentase
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