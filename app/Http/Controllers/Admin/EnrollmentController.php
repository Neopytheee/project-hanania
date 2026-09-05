<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use App\Services\EnrollmentService;

class EnrollmentController extends Controller
{
    // 💡 Tambahkan Request $request di dalam parameter
    public function index(Request $request, EnrollmentService $service)
    {
        // 1. Siapkan Query Utama (Sama persis dengan relasi yang bosku panggil sebelumnya)
        $query = Enrollment::with([
            'customer', 
            'travelPackage', 
            'paymentPlan.transactions', 
            'documents'
        ]);

        // 2. Logika Smart Filter (Pencarian Text)
        if ($request->filled('search')) {
            $search = $request->search;
            
            // Kita bungkus dalam closure function($q) agar query OR-nya tidak bentrok
            $query->where(function($q) use ($search) {
                $q->where('enrollment_number', 'like', "%{$search}%")
                  ->orWhere('passenger_name', 'like', "%{$search}%")
                  ->orWhereHas('customer', function($qCustomer) use ($search) {
                      $qCustomer->where('name', 'like', "%{$search}%"); // Cari berdasarkan nama pembayar
                  });
            });
        }

        // 3. Logika Filter Status (Aktif / Lunas / Batal)
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // 4. Ambil Data dengan Pagination (PENTING!)
        // Diganti dari get() menjadi paginate(10) agar kalau datanya sudah ratusan, website tidak lemot.
        $enrollments = $query->orderBy('created_at', 'desc')->paginate(10);

        // 5. Hitung progres keuangan masing-masing jamaah (Kodingan bosku tetap aman!)
        foreach($enrollments as $enrollment) {
            $enrollment->progress_data = $service->calculateProgress($enrollment);
        }

        return view('admin.enrollments.index', compact('enrollments'));
    }

    public function show(Enrollment $enrollment, EnrollmentService $service)
    {
        // Kodingan fungsi show bosku 100% AMAN, tidak perlu diubah.
        $enrollment->load([
            'customer', 
            'travelPackage', 
            'paymentPlan.transactions' => function($query) {
                $query->orderBy('created_at', 'desc');
            }, 
            'documents',
            'groupMemberships.group.departure'
        ]);

        $progress = $service->calculateProgress($enrollment);

        return view('admin.enrollments.show', compact('enrollment', 'progress'));
    }
}