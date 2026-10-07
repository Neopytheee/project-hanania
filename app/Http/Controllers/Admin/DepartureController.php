<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Departure;
use App\Models\Group;
use App\Models\TravelPackage;
use App\Services\DepartureService;
use App\Services\GroupService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DepartureController extends Controller
{
    protected DepartureService $departureService;

    public function __construct(DepartureService $departureService)
    {
        $this->departureService = $departureService;
    }

    public function index()
    {
        $departures = Departure::with('travelPackage')->orderBy('departure_date', 'asc')->get();
        $packages = TravelPackage::where('status', 'active')->get();

        return view('admin.departures.index', compact('departures', 'packages'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'travel_package_id' => 'required|exists:travel_packages,id',
            'name' => 'required|string|max:255',
            'departure_date' => 'required|date',
            'quota' => 'required|integer|min:1',
            'estimated_price' => 'nullable|numeric|min:0', // Nullable karena Service Anda sudah punya fallback
        ]);

        // Ambil Model Travel Package sesuai kebutuhan Service Anda
        $travelPackage = TravelPackage::findOrFail($validated['travel_package_id']);

        // Panggil Service Anda
        $this->departureService->create($travelPackage, $validated);

        return redirect()->back()->with('success', 'Jadwal Keberangkatan berhasil ditambahkan (Status: Draft)!');
    }

    /**
     * ==========================================
     * FUNGSI BARU: Tampilkan Halaman Detail Jadwal
     * ==========================================
     */
    public function show(Departure $departure)
    {
        // Load data jadwal beserta rombongan dan jamaah aktifnya
        $departure->load(['travelPackage', 'groups.activeMemberships.enrollment.customer']);

        // Cari rombongan yang masih "nganggur" (belum punya departure_id)
        $availableGroups = Group::whereNull('departure_id')->get();

        return view('admin.departures.show', compact('departure', 'availableGroups'));
    }

    /**
     * ==========================================
     * FUNGSI BARU: Tarik Rombongan ke Jadwal Ini
     * ==========================================
     */
    public function assignGroup(Request $request, Departure $departure)
    {
        $request->validate([
            'group_id' => 'required|exists:groups,id',
        ]);

        $group = Group::findOrFail($request->group_id);

        try {
            // Panggil GroupService untuk mengaitkan grup sekaligus mengunci harga final jamaah
            app(GroupService::class)->assignToDeparture($group, $departure);

            return back()->with('success', "Alhamdulillah, Rombongan '{$group->name}' berhasil ditarik ke jadwal ini. Harga final jamaah telah disesuaikan!");
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function depart(Departure $departure, DepartureService $departureService)
    {
        // 1. Eksekusi logika dari Service
        $departureService->markAsDeparted($departure);

        // 2. Kembalikan halaman beserta notifikasi
        return back()->with('success', 'Alhamdulillah! Rombongan resmi diberangkatkan. Status semua jamaah telah diubah.');
    }

    /**
     * Update info Manasik & Itinerary untuk Jamaah
     */
    public function updatePersiapan(Request $request, $id)
    {
        $departure = Departure::findOrFail($id);

        $validated = $request->validate([
            'manasik_date' => 'nullable|date',
            'manasik_location' => 'nullable|string|max:255',
            'itinerary_file' => 'nullable|mimes:pdf|max:5120', // Maks 5MB
        ]);

        // Tangani jika Admin upload file PDF baru
        if ($request->hasFile('itinerary_file')) {
            // Hapus file lama jika sebelumnya sudah pernah upload
            if ($departure->itinerary_file) {
                Storage::disk('public')->delete($departure->itinerary_file);
            }
            // Simpan file baru
            $validated['itinerary_file'] = $request->file('itinerary_file')->store('itineraries', 'public');
        }

        $departure->update($validated);

        return back()->with('success', 'Info Manasik dan Itinerary berhasil diperbarui! Jamaah kini bisa melihatnya di Dashboard mereka.');
    }

    /**
     * Eksekusi penyelesaian ibadah kepulangan jamaah
     */
    public function complete(Departure $departure, DepartureService $departureService)
    {
        $departureService->markAsCompleted($departure);

        return back()->with('success', 'Alhamdulillah! Seluruh rangkaian ibadah telah selesai dan jamaah telah kembali. Status diperbarui menjadi Selesai (Completed).');
    }

    /**
     * Mengeluarkan Rombongan dari Jadwal
     */
    public function removeGroup(Departure $departure, Group $group, DepartureService $departureService)
    {
        try {
            // Memanggil logika dari DepartureService
            $departureService->removeGroup($departure, $group->id);

            return back()->with('success', "Rombongan {$group->name} berhasil dikeluarkan dari jadwal.");
        } catch (\Exception $e) {
            return back()->withErrors([$e->getMessage()]);
        }
    }

    /**
     * Menghapus Jadwal Keberangkatan
     */
    public function destroy(Departure $departure, DepartureService $departureService)
    {
        try {
            // Memanggil logika dari DepartureService
            $departureService->delete($departure);

            return redirect()->route('admin.departures.index')->with('success', 'Jadwal keberangkatan berhasil dihapus dan semua rombongan telah dikosongkan.');
        } catch (\Exception $e) {
            return back()->withErrors([$e->getMessage()]);
        }
    }

    public function updateItinerary(Request $request, Departure $departure)
    {
        $request->validate([
            'itinerary_file' => 'required|file|mimes:pdf,doc,docx|max:5120', // Maksimal 5MB
        ]);

        if ($request->hasFile('itinerary_file')) {
            // Hapus file lama jika ada
            if ($departure->itinerary_file && Storage::disk('public')->exists($departure->itinerary_file)) {
                Storage::disk('public')->delete($departure->itinerary_file);
            }

            // Simpan file baru
            $path = $request->file('itinerary_file')->store('itineraries', 'public');
            $departure->update(['itinerary_file' => $path]);
        }

        return back()->with('success', 'Dokumen itinerary berhasil diperbarui!');
    }
}
