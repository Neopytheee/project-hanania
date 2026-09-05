<?php

namespace App\Services;

use App\Models\Departure;
use App\Models\Enrollment;
use Illuminate\Support\Facades\DB;

class PricingService
{
    public function finalizeDeparturePrice(
        Departure $departure,
        float $finalPrice,
        int $adminId
    ): Departure {
        if ($finalPrice <= 0) {
            throw new \InvalidArgumentException(
                'Harga final harus lebih dari 0.'
            );
        }

        return DB::transaction(function () use (
            $departure,
            $finalPrice,
            $adminId
        ) {
            if (!in_array(
                $departure->status,
                ['open', 'filling', 'full', 'closed'],
                true
            )) {
                throw new \RuntimeException(
                    'Departure belum dapat difinalisasi harganya.'
                );
            }

            $departure->update([
                'final_price' => $finalPrice,
                'finalized_at' => now(),
                'finalized_by' => $adminId,
            ]);

            /*
             * Semua enrollment aktif pada departure
             * mendapatkan harga final.
             */
            $enrollments = Enrollment::query()
                ->whereHas(
                    'groupMemberships',
                    function ($query) use ($departure) {
                        $query->where(
                            'status',
                            'active'
                        )->whereHas(
                            'group',
                            function ($query) use ($departure) {
                                $query->where(
                                    'departure_id',
                                    $departure->id
                                );
                            }
                        );
                    }
                )
                ->with('paymentPlan')
                ->lockForUpdate()
                ->get();

            foreach ($enrollments as $enrollment) {
                $enrollment->update([
                    'final_price' => $finalPrice,
                    'status' => 'price_confirmed',
                    'price_confirmed_at' => now(),
                ]);

                $enrollment->paymentPlan?->update([
                    'final_target_amount' => $finalPrice,
                    'status' => 'finalized',
                    'finalized_at' => now(),

                    /*
                     * V1: baseline H-30.
                     */
                    'due_date' => $departure
                        ->departure_date
                        ->copy()
                        ->subDays(30),
                ]);
            }

            return $departure->fresh();
        });
    }

    public function outstanding(
        Enrollment $enrollment
    ): float {
        $finalPrice = $enrollment->final_price;

        if ($finalPrice === null) {
            return 0;
        }

        $paid = $enrollment->verifiedPaymentTotal();

        return max(
            (float) $finalPrice - $paid,
            0
        );
    }
}