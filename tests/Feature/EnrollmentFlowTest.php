<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\TravelPackage;
use App\Models\User;
use App\Services\EnrollmentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Exception;

class EnrollmentFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_cannot_have_more_than_two_pending_enrollments(): void
    {
        // PERSIAPAN DATA (Robot menyamar jadi 'Bagas')
        $user = User::factory()->create(['role' => 'customer']);
        $customer = Customer::create([
            'user_id' => $user->id,
            'customer_number' => 'CUS-TEST-004',
            'name' => 'Bagas',
            'phone' => '08333333333',
            'email' => 'bagas@test.com',
            'status' => 'active',
        ]);

        $package1 = TravelPackage::create(['code' => 'PKG-004', 'name' => 'Umroh 1', 'estimated_price' => 30000000, 'duration_days' => 9, 'status' => 'active']);
        $package2 = TravelPackage::create(['code' => 'PKG-005', 'name' => 'Umroh 2', 'estimated_price' => 30000000, 'duration_days' => 9, 'status' => 'active']);
        $package3 = TravelPackage::create(['code' => 'PKG-006', 'name' => 'Umroh 3', 'estimated_price' => 30000000, 'duration_days' => 9, 'status' => 'active']);

        $service = app(EnrollmentService::class);

        // UJIAN DIMULAI:
        // Pendaftaran 1 (Harusnya Sukses)
        $service->create($customer, $package1);
        
        // Pendaftaran 2 (Harusnya Sukses - Tapi kuota 'Pending' sudah penuh)
        $service->create($customer, $package2);

        // Pendaftaran 3 (HARUS DITOLAK karena melanggar batas Lapis 1)
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Selesaikan dulu pembayaran setoran awal pada pendaftaran Anda sebelumnya');

        $service->create($customer, $package3);
    }

    public function test_customer_cannot_enroll_in_the_same_package_twice_if_active(): void
    {
        // PERSIAPAN DATA (Robot menyamar jadi 'Rina')
        $user = User::factory()->create(['role' => 'customer']);
        $customer = Customer::create([
            'user_id' => $user->id,
            'customer_number' => 'CUS-TEST-005',
            'name' => 'Rina',
            'phone' => '08444444444',
            'email' => 'rina@test.com',
            'status' => 'active',
        ]);

        $package = TravelPackage::create([
            'code' => 'PKG-007', 
            'name' => 'Umroh VIP', 
            'estimated_price' => 45000000, 
            'duration_days' => 12, 
            'status' => 'active'
        ]);

        $service = app(EnrollmentService::class);

        // UJIAN DIMULAI:
        // Rina daftar Paket VIP (Sukses)
        $service->create($customer, $package);

        // Rina iseng daftar Paket VIP lagi untuk dirinya sendiri (HARUS DITOLAK karena melanggar batas Lapis 2)
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Nama jamaah ini sudah terdaftar di paket tersebut');

        $service->create($customer, $package);
    }
}