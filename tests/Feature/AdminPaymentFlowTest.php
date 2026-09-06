<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\TravelPackage;
use App\Models\User;
use App\Services\EnrollmentService;
use App\Services\PaymentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use RuntimeException;
use InvalidArgumentException;

class AdminPaymentFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUpData()
    {
        // 1. Buat Akun Admin
        $admin = User::factory()->create(['role' => 'admin']);

        // 2. Buat Akun Jamaah
        $customerUser = User::factory()->create(['role' => 'customer']);
        $customer = Customer::create([
            'user_id' => $customerUser->id,
            'customer_number' => 'CUS-ADM-001',
            'name' => 'Wati (Jamaah)',
            'phone' => '08999999999',
            'email' => 'wati@test.com',
            'status' => 'active',
        ]);

        // 3. Buat Paket & Pendaftaran
        $package = TravelPackage::create([
            'code' => 'PKG-ADM-001',
            'name' => 'Paket Umroh Admin Test',
            'estimated_price' => 30000000,
            'duration_days' => 9,
            'status' => 'active',
        ]);

        $enrollment = app(EnrollmentService::class)->create($customer, $package);
        
        // 4. Buat Transaksi Pending (Wati upload bukti transfer)
        $transaction = app(PaymentService::class)->createPayment($enrollment, 1000000, 'initial_deposit', 'manual_transfer', null, 'bukti.jpg');

        return [$admin, $transaction];
    }

    public function test_admin_can_verify_pending_payment_successfully(): void
    {
        [$admin, $transaction] = $this->setUpData();
        $paymentService = app(PaymentService::class);

        // SKENARIO 1: Admin memverifikasi pembayaran yang sah
        $verifiedTransaction = $paymentService->verifyPayment($transaction, $admin);

        $this->assertEquals('verified', $verifiedTransaction->status);
        $this->assertEquals($admin->id, $verifiedTransaction->verified_by);
        
        // Pastikan uangnya benar-benar tercatat di buku tabungan jamaah
        $this->assertDatabaseHas('payment_transactions', [
            'id' => $transaction->id,
            'status' => 'verified',
            'verified_by' => $admin->id,
        ]);
    }

    public function test_admin_cannot_verify_expired_or_processed_payment(): void
    {
        [$admin, $transaction] = $this->setUpData();
        
        // KITA UBAH STATUS TRANSAKSI JADI KADALUARSA (Expired)
        $transaction->update(['status' => 'expired']);

        $paymentService = app(PaymentService::class);

        // SKENARIO 2: Admin teledor/hacker mencoba paksa verifikasi data yang expired
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Pembayaran tidak berada dalam status pending');

        $paymentService->verifyPayment($transaction, $admin);
    }

    public function test_admin_can_reject_payment_with_valid_reason(): void
    {
        [$admin, $transaction] = $this->setUpData();
        $paymentService = app(PaymentService::class);

        // SKENARIO 3: Admin menolak karena bukti transfer buram
        $rejectedTransaction = $paymentService->rejectPayment($transaction, $admin, 'Bukti transfer buram/tidak terbaca.');

        $this->assertEquals('rejected', $rejectedTransaction->status);
        $this->assertEquals('Bukti transfer buram/tidak terbaca.', $rejectedTransaction->rejection_reason);
    }

    public function test_admin_cannot_reject_payment_without_reason(): void
    {
        [$admin, $transaction] = $this->setUpData();
        $paymentService = app(PaymentService::class);

        // SKENARIO 4: Admin asal klik tolak tanpa kasih alasan ke jamaah
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Alasan penolakan wajib diisi');

        // Mengirim string kosong sebagai alasan
        $paymentService->rejectPayment($transaction, $admin, '   ');
    }
}