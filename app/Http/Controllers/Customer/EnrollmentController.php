<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEnrollmentRequest;
use App\Models\Enrollment;
use App\Models\TravelPackage;
use App\Services\EnrollmentService;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    protected EnrollmentService $enrollmentService;

    public function __construct(EnrollmentService $enrollmentService)
    {
        $this->enrollmentService = $enrollmentService;
    }

    public function index(Request $request)
    {
        $customer = $request->user()->customer;
        
        $enrollments = Enrollment::with('travelPackage', 'paymentPlan')
            ->where('customer_id', $customer->id)
            ->orderBy('created_at', 'desc') // Urutkan dari yang terbaru
            ->get();

        // Hitung progres untuk setiap kotak tabungan
        foreach ($enrollments as $enrollment) {
            $enrollment->progress = $this->enrollmentService->calculateProgress($enrollment);
        }

        return view('customer.enrollments.index', compact('enrollments'));
    }

    public function store(StoreEnrollmentRequest $request)
    {
        /** @var \App\Models\User $user */
        $user = $request->user();
        $customer = $user->customer;

        $validated = $request->validated();
        $package = TravelPackage::findOrFail($validated['travel_package_id']);

        // BUNGKUS DENGAN TRY-CATCH
        try {
            // TERUSKAN $request->all() KE DALAM SERVICE DI SINI 👇
            $enrollment = $this->enrollmentService->create($customer, $package, $request->all());

            return redirect()->route('customer.enrollments.show', $enrollment->id)
                         ->with('success', 'Alhamdulillah, Anda berhasil mulai menabung untuk paket ' . $package->name);
                         
        } catch (\Exception $e) {
            // TANGKAP ERROR DARI SERVICE (VALIDASI ANTI-SPAM & GANDA)
            // Dan lempar kembali sebagai pesan error (flash message)
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    /**
     * Menampilkan halaman detail tabungan (Ruang Kontrol)
     */
    public function show(Request $request, Enrollment $enrollment, EnrollmentService $enrollmentService, PaymentService $paymentService)
    {
        // Pastikan jamaah hanya bisa melihat datanya sendiri
        if ($enrollment->customer_id !== $request->user()->customer->id) {
            abort(403, 'Anda tidak memiliki akses ke data ini.');
        }

        // Bersihkan transaksi kadaluarsa via Service
        $enrollmentService->cleanupExpiredTransactions($enrollment);

        $enrollment->load([
            'travelPackage', 
            'paymentPlan.transactions',
            'groupMemberships.group.departure'
        ]);

        $paymentRules = $paymentService->getNextPaymentRules($enrollment);
        
        // --- TAMBAHAN BARU: Panggil perhitungan progres dari Service ---
        $progress = $enrollmentService->calculateProgress($enrollment);

        // Lempar variabel $progress ke tampilan (view)
        return view('customer.enrollments.show', compact('enrollment', 'paymentRules', 'progress'));
    }
}