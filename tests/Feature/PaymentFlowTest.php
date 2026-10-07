<?php

namespace Tests\Feature;

use App\Models\AppInformation;
use App\Models\Customer;
use App\Models\TravelPackage;
use App\Models\User;
use App\Services\EnrollmentService;
use App\Services\PaymentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_midtrans_payment_can_be_disabled_from_app_settings(): void
    {
        AppInformation::updateOrCreate(
            ['key' => 'payment_midtrans_enabled'],
            ['value' => '0']
        );

        $this->assertFalse(AppInformation::isPaymentMidtransEnabled());
    }

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
            'code' => 'PKG-001',
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
        ]);

        $paymentService = app(PaymentService::class);

        // Budi bayar DP 1 Juta (Harusnya Sukses)
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

    public function test_initial_deposit_must_meet_minimum(): void
    {
        $user = User::factory()->create(['role' => 'customer']);

        // DILENGKAPI: Tambah customer_number, phone, dan email
        $customer = Customer::create([
            'user_id' => $user->id,
            'customer_number' => 'CUS-TEST-002',
            'name' => 'Joko Iseng',
            'phone' => '08111111111',
            'email' => 'joko@test.com',
            'status' => 'active',
        ]);

        // DILENGKAPI: Tambah code dan duration_days
        $package = TravelPackage::create([
            'code' => 'PKG-002',
            'name' => 'Umroh Reguler',
            'description' => 'Test Package 2',
            'estimated_price' => 35000000,
            'duration_days' => 9,
            'status' => 'active',
        ]);

        $enrollment = app(EnrollmentService::class)->create($customer, $package);
        $paymentService = app(PaymentService::class);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Setoran awal (DP) wajib minimal');

        $paymentService->createPayment($enrollment, 500000, 'initial_deposit');
    }

    public function test_additional_payment_must_meet_minimum(): void
    {
        $user = User::factory()->create(['role' => 'customer']);

        $customer = Customer::create([
            'user_id' => $user->id,
            'customer_number' => 'CUS-TEST-003',
            'name' => 'Siti',
            'phone' => '08222222222',
            'email' => 'siti@test.com',
            'status' => 'active',
        ]);

        $package = TravelPackage::create([
            'code' => 'PKG-003',
            'name' => 'Umroh Hemat',
            'description' => 'Test Package 3',
            'estimated_price' => 25000000,
            'duration_days' => 9,
            'status' => 'active',
        ]);

        $enrollment = app(EnrollmentService::class)->create($customer, $package);
        $paymentService = app(PaymentService::class);

        $dp = $paymentService->createPayment($enrollment, 1000000, 'initial_deposit');
        $dp->update(['status' => 'verified']);

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Setoran tabungan lanjutan minimal adalah');

        $paymentService->createPayment($enrollment, 300000, 'additional_payment');
    }

    public function test_midtrans_qris_payment_records_fee_and_net_amount(): void
    {
        config()->set('services.midtrans.fee_rates.qris', 0.0075);

        $user = User::factory()->create(['role' => 'customer']);
        $customer = Customer::create([
            'user_id' => $user->id,
            'customer_number' => 'CUS-TEST-004',
            'name' => 'Rina',
            'phone' => '08333333333',
            'email' => 'rina@test.com',
            'status' => 'active',
        ]);
        $package = TravelPackage::create([
            'code' => 'PKG-004',
            'name' => 'Umroh Plus',
            'description' => 'Test Package 4',
            'estimated_price' => 30000000,
            'duration_days' => 10,
            'status' => 'active',
        ]);

        $enrollment = app(EnrollmentService::class)->create($customer, $package);
        $payment = app(PaymentService::class)->createPayment(
            $enrollment,
            1000000,
            'initial_deposit',
            'midtrans',
            null,
            null,
            'qris'
        );

        $this->assertSame(1000000.0, (float) $payment->amount);
        $this->assertSame(8000.0, (float) $payment->fee_amount);
        $this->assertSame(1000000.0, (float) $payment->net_amount);
        $this->assertSame('qris', $payment->midtrans_channel);
    }

    public function test_midtrans_without_explicit_channel_uses_default_fee_rate(): void
    {
        config()->set('services.midtrans.fee_rates.bank_transfer', 0.008);
        config()->set('services.midtrans.default_fee_rate', 0.008);

        $user = User::factory()->create(['role' => 'customer']);
        $customer = Customer::create([
            'user_id' => $user->id,
            'customer_number' => 'CUS-TEST-005A',
            'name' => 'Eko',
            'phone' => '08555555555',
            'email' => 'eko@test.com',
            'status' => 'active',
        ]);
        $package = TravelPackage::create([
            'code' => 'PKG-005A',
            'name' => 'Umroh Family',
            'description' => 'Test Package 5A',
            'estimated_price' => 35000000,
            'duration_days' => 12,
            'status' => 'active',
        ]);

        $enrollment = app(EnrollmentService::class)->create($customer, $package);
        $payment = app(PaymentService::class)->createPayment(
            $enrollment,
            1000000,
            'initial_deposit',
            'midtrans',
            null,
            null,
            null
        );

        $this->assertSame(8000.0, (float) $payment->fee_amount);
        $this->assertSame(1000000.0, (float) $payment->net_amount);
        $this->assertSame('bank_transfer', $payment->midtrans_channel);
    }

    public function test_verified_midtrans_payment_uses_net_amount_for_customer_balance(): void
    {
        config()->set('services.midtrans.fee_rates.bca_va', 0.0065);

        $user = User::factory()->create(['role' => 'customer']);
        $customer = Customer::create([
            'user_id' => $user->id,
            'customer_number' => 'CUS-TEST-005',
            'name' => 'Dewi',
            'phone' => '08444444444',
            'email' => 'dewi@test.com',
            'status' => 'active',
        ]);
        $package = TravelPackage::create([
            'code' => 'PKG-005',
            'name' => 'Umroh Family',
            'description' => 'Test Package 5',
            'estimated_price' => 35000000,
            'duration_days' => 12,
            'status' => 'active',
        ]);

        $enrollment = app(EnrollmentService::class)->create($customer, $package);
        $payment = app(PaymentService::class)->createPayment(
            $enrollment,
            2200000,
            'initial_deposit',
            'midtrans',
            null,
            null,
            'bca_va'
        );
        $payment->update(['status' => 'verified']);

        $progress = app(EnrollmentService::class)->calculateProgress($enrollment);

        $this->assertSame(2200000.0, (float) $progress['total_dibayar']);
        $this->assertSame(32800000.0, (float) $progress['sisa_tagihan']);
    }
}
