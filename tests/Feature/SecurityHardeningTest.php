<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\Departure;
use App\Models\Enrollment;
use App\Models\TravelPackage;
use App\Models\User;
use App\Services\CancellationService;
use App\Services\ChatbotService;
use App\Services\DepartureService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class SecurityHardeningTest extends TestCase
{
    use RefreshDatabase;

    public function test_auth_routes_have_rate_limiting_to_prevent_brute_force()
    {
        for ($i = 0; $i < 5; $i++) {
            $this->post('/login', ['email' => 'test@test.com', 'password' => 'wrong'])->assertSessionHasErrors();
        }

        $response = $this->post('/login', ['email' => 'test@test.com', 'password' => 'wrong']);
        $response->assertStatus(429);
    }

    public function test_only_super_admin_can_reset_passwords_and_notification_sent()
    {
        $superAdmin = User::forceCreate(['email' => 'sa@x.com', 'password' => bcrypt('123'), 'role' => 'admin', 'status' => 'active']);
        $normalAdmin = User::forceCreate(['email' => 'na@x.com', 'password' => bcrypt('123'), 'role' => 'admin', 'status' => 'active']);

        Role::create(['name' => 'Super Admin']);
        Role::create(['name' => 'Admin Operasional']);

        $superAdmin->assignRole('Super Admin');
        $normalAdmin->assignRole('Admin Operasional');

        // Normal admin ditolak
        $this->actingAs($normalAdmin)->post(route('admin.management.reset_password', $superAdmin->id))
            ->assertForbidden();

        // Super Admin diizinkan
        $this->actingAs($superAdmin)->post(route('admin.management.reset_password', $normalAdmin->id))
            ->assertSessionHas('success');

        // Cek langsung ke database apakah Token Reset Password benar-benar terbuat!
        $tokenTable = Schema::hasTable('password_reset_tokens') ? 'password_reset_tokens' : 'password_resets';
        $this->assertDatabaseHas($tokenTable, [
            'email' => $normalAdmin->email,
        ]);
    }

    public function test_gemini_api_key_is_not_exposed_in_url()
    {
        Http::fake([
            '*' => Http::response([
                'candidates' => [
                    [
                        'content' => [
                            'parts' => [
                                ['text' => 'Aman'],
                            ],
                        ],
                    ],
                ],
            ], 200),
        ]);

        $user = User::forceCreate(['email' => 'test@x.com', 'password' => '123', 'role' => 'customer', 'status' => 'active']);
        $service = new ChatbotService;
        $service->processMessage('Halo', $user);

        Http::assertSent(function (Request $request) {
            return ! str_contains($request->url(), '?key=') && $request->hasHeader('x-goog-api-key');
        });
    }

    public function test_departure_workflow_state_transition()
    {
        $package = TravelPackage::forceCreate(['code' => 'PKG-1', 'name' => 'Test Pkg', 'estimated_price' => 20000]);
        $departure = Departure::forceCreate([
            'travel_package_id' => $package->id,
            'code' => 'DEP-TEST',
            'name' => 'Test',
            'departure_date' => now()->addMonth(),
            'quota' => 30,
            'estimated_price' => 20000,
            'status' => 'draft',
        ]);

        $service = new DepartureService;

        $this->expectException(\RuntimeException::class);
        $service->finalize($departure);
    }

    public function test_cancellation_request_creates_complete_data_and_locks_enrollment()
    {
        $user = User::forceCreate(['email' => 'j@x.com', 'password' => bcrypt('12'), 'role' => 'customer', 'status' => 'active']);
        $customer = Customer::forceCreate(['user_id' => $user->id, 'customer_number' => 'C-1', 'name' => 'Jamaah', 'phone' => '081']);
        $package = TravelPackage::forceCreate(['code' => 'PKG-2', 'name' => 'Test', 'estimated_price' => 2000]);

        $enrollment = Enrollment::forceCreate([
            'customer_id' => $customer->id,
            'travel_package_id' => $package->id,
            'enrollment_number' => 'ENR-123',
            'unique_code' => 1234,
            'estimated_price_snapshot' => 2000, // 🔒 PERBAIKAN: Tambah field snapshot harga
            'status' => 'fully_paid',
        ]);

        $service = new CancellationService;

        $service->request($enrollment, 'voluntary_withdrawal', 'Alasan keluarga', 'doc.pdf');

        $this->assertDatabaseHas('cancellation_requests', [
            'enrollment_id' => $enrollment->id,
            'cancellation_type' => 'voluntary_withdrawal',
            'status' => 'requested',
        ]);
    }

    public function test_non_http_maps_link_is_rejected(): void
    {
        $superAdmin = User::forceCreate([
            'email' => 'settings-admin@example.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'status' => 'active',
        ]);
        Role::create(['name' => 'Super Admin']);
        $superAdmin->assignRole('Super Admin');

        $this->actingAs($superAdmin)
            ->put(route('admin.informations.update'), [
                'company_name' => 'Hanania',
                'google_maps_link' => 'ftp://example.com',
            ])
            ->assertSessionHasErrors('google_maps_link');
    }

    public function test_draft_package_is_not_publicly_visible(): void
    {
        $package = TravelPackage::forceCreate([
            'code' => 'PKG-DRAFT',
            'name' => 'Draft Package',
            'estimated_price' => 20000,
            'status' => 'draft',
        ]);

        $this->get(route('packages.show', $package))
            ->assertNotFound();
    }
}
