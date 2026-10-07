<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Enrollment;
use App\Models\PaymentPlan;
use App\Models\TravelPackage;
use Exception;
use Illuminate\Support\Facades\DB;

class EnrollmentService
{
    public function create(Customer $customer, TravelPackage $travelPackage, array $data = []): Enrollment
    {
        $relationship = $data['relationship'] ?? 'Diri Sendiri';
        $passengerName = ($relationship === 'Diri Sendiri')
            ? $customer->name
            : ($data['passenger_name'] ?? $customer->name);

        return DB::transaction(function () use ($customer, $travelPackage, $passengerName, $relationship) {

            $lockedCustomer = Customer::where('id', $customer->id)->lockForUpdate()->first();

            $pendingCount = Enrollment::where('customer_id', $lockedCustomer->id)
                ->whereIn('status', ['enrolled', 'payment_due'])
                ->count();

            if ($pendingCount >= 2) {
                throw new Exception('Selesaikan dulu pembayaran setoran awal pada pendaftaran Anda sebelumnya sebelum memilih paket baru.');
            }

            $isDuplicate = Enrollment::where('customer_id', $lockedCustomer->id)
                ->where('travel_package_id', $travelPackage->id)
                ->where('passenger_name', $passengerName)
                ->whereNotIn('status', ['cancelled', 'completed'])
                ->exists();

            if ($isDuplicate) {
                throw new Exception('Nama jamaah ini sudah terdaftar di paket tersebut.');
            }

            do {
                $uniqueCode = random_int(100, 9999);
            } while (Enrollment::where('unique_code', $uniqueCode)->exists());

            $enrollment = Enrollment::create([
                'enrollment_number' => $this->generateEnrollmentNumber($travelPackage),
                'customer_id' => $lockedCustomer->id,
                'passenger_name' => $passengerName,
                'relationship' => $relationship,
                'travel_package_id' => $travelPackage->id,
                'unique_code' => $uniqueCode, // Gunakan variabel yang sudah divalidasi bebas bentrok
                'estimated_price_snapshot' => $travelPackage->estimated_price,
                'status' => 'enrolled',
            ]);

            PaymentPlan::create([
                'enrollment_id' => $enrollment->id,
                'estimated_target_amount' => $travelPackage->estimated_price,
                'minimum_initial_payment' => 1000000,
                'minimum_monthly_payment' => 500000,
                'status' => 'active',
            ]);

            return $enrollment;
        });
    }

    public function cleanupExpiredTransactions(Enrollment $enrollment): void
    {
        if ($enrollment->paymentPlan) {
            $enrollment->paymentPlan->transactions()
                ->where('status', 'pending')
                ->whereNotNull('snap_token')
                ->where('created_at', '<', now()->subMinutes(5))
                ->update(['status' => 'expired']);
        }
    }

    protected function generateEnrollmentNumber(TravelPackage $travelPackage): string
    {
        $prefix = ($travelPackage->category === 'HAJI') ? 'HJI' : 'UMR';

        do {
            $number = $prefix.'-'.now()->format('Ym').'-'.random_int(100000, 999999);
        } while (Enrollment::where('enrollment_number', $number)->exists());

        return $number;
    }

    public function calculateProgress(Enrollment $enrollment): array
    {
        $totalHarga = $enrollment->final_price ?? $enrollment->estimated_price_snapshot ?? 0;

        $totalDibayar = 0;
        if ($enrollment->paymentPlan) {
            $totalDibayar = (float) $enrollment->paymentPlan->transactions()
                ->where('status', 'verified')
                ->sum(DB::raw('COALESCE(net_amount, amount)'));
        }

        $sisaTagihan = max(0, $totalHarga - $totalDibayar);
        $persentase = 0;

        if ($totalHarga > 0) {
            $persentase = ($totalDibayar / $totalHarga) * 100;
            $persentase = $persentase > 100 ? 100 : round($persentase, 1);
        }

        return [
            'total_harga' => $totalHarga,
            'total_dibayar' => $totalDibayar,
            'sisa_tagihan' => $sisaTagihan,
            'persentase' => $persentase,
        ];
    }
}
