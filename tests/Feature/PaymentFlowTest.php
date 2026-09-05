<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\PaymentPlan;
use App\Models\TravelPackage;
use App\Models\User;
use App\Services\EnrollmentService;
use App\Services\PaymentService;
use App\Services\SavingPeriodService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_can_enroll_and_make_initial_deposit(): void
    {
        $user = User::factory()->create([
            'role' => 'customer',
        ]);

        $customer = Customer::create([
            'user_id' => $user->id,
            'customer_number' => 'CUS-TEST-001',
            'name' => 'Budi',
            'phone' => '08123456789',
            'email' => 'budi@test.com',
            'status' => 'active',
        ]);

        $package = TravelPackage::create([
            'code' => 'TEST-001',
            'name' => 'Umroh Reguler 12 Hari',
            'description' => 'Test Package',
            'estimated_price' => 35000000,
            'duration_days' => 12,
            'status' => 'active',
        ]);

        $enrollmentService = app(EnrollmentService::class);

        $enrollment = $enrollmentService->create(
            $customer,
            $package
        );

        $this->assertDatabaseHas('enrollments', [
            'id' => $enrollment->id,
            'customer_id' => $customer->id,
            'travel_package_id' => $package->id,
            'estimated_price_snapshot' => 35000000,
        ]);

        $this->assertDatabaseHas('payment_plans', [
            'enrollment_id' => $enrollment->id,
            'minimum_initial_payment' => 1000000,
            'minimum_monthly_payment' => 500000,
            'monthly_due_day' => 25,
        ]);

        $paymentService = app(PaymentService::class);

        $payment = $paymentService->createPayment(
            $enrollment,
            1000000,
            'initial_deposit'
        );

        $this->assertDatabaseHas('payment_transactions', [
            'id' => $payment->id,
            'type' => 'initial_deposit',
            'amount' => 1000000,
            'status' => 'pending',
        ]);
    }

    public function test_monthly_payment_must_meet_minimum(): void
    {
        $user = User::factory()->create([
            'role' => 'customer',
        ]);

        $customer = Customer::create([
            'user_id' => $user->id,
            'customer_number' => 'CUS-TEST-002',
            'name' => 'Budi',
            'phone' => '08123456789',
            'status' => 'active',
        ]);

        $package = TravelPackage::create([
            'code' => 'TEST-002',
            'name' => 'Umroh Reguler 12 Hari',
            'estimated_price' => 35000000,
            'duration_days' => 12,
            'status' => 'active',
        ]);

        $enrollment = app(EnrollmentService::class)->create(
            $customer,
            $package
        );

        $period = app(SavingPeriodService::class)
            ->createCurrentPeriod(
                $enrollment->paymentPlan
            );

        $paymentService = app(PaymentService::class);

        $this->expectException(\InvalidArgumentException::class);

        $paymentService->createPayment(
            $enrollment,
            300000,
            'monthly_payment',
            'bank_transfer',
            $period->id
        );
    }
}