<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{   
    public function index(\App\Models\Enrollment $enrollment)
    {
        // Pastikan jamaah hanya bisa melihat dokumennya sendiri
        if ($enrollment->customer_id !== auth()->user()->customer->id) {
            abort(403, 'Akses ditolak.');
        }

        return view('customer.documents.index', compact('enrollment'));
    }

    public function store(Request $request, Enrollment $enrollment)
    {
        // Pastikan milik jamaah itu sendiri
        if ($enrollment->customer_id !== $request->user()->customer->id) {
            abort(403, 'Akses ditolak.');
        }

        $request->validate([
            'document_type' => 'required|in:ktp,kk,passport_biodata,passport_endorsement,photo,other',
            'file_path'     => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120', // Maksimal 5MB
        ]);

        $file = $request->file('file_path');
        $path = $file->store('dokumen_jamaah/' . $enrollment->id, 'public');

        // Cari dokumen lama jika ada
        $existingDoc = Document::where('enrollment_id', $enrollment->id)
                               ->where('document_type', $request->document_type)
                               ->first();

        // Hapus file fisik yang lama agar server tidak penuh
        if ($existingDoc && Storage::disk('public')->exists($existingDoc->file_path)) {
            Storage::disk('public')->delete($existingDoc->file_path);
        }

        // Simpan atau Update ke database
        Document::updateOrCreate(
            ['enrollment_id' => $enrollment->id, 'document_type' => $request->document_type],
            [
                'file_path' => $path,
                'status'    => 'submitted', // Reset status ke submitted
                'rejection_reason' => null, // Hapus alasan penolakan sebelumnya
                'uploaded_at' => now(),
            ]
        );

        return back()->with('success', 'Dokumen ' . strtoupper($request->document_type) . ' berhasil diunggah dan sedang direview.');
    }

    /**
     * Memaksa browser untuk melakukan PREVIEW file (bukan download)
     */
    public function preview($id)
    {
        // Sesuaikan nama model Document dengan yang bosku pakai
        $document = \App\Models\Document::findOrFail($id); 
        
        // Ambil path fisik file di dalam folder storage Laravel
        $path = storage_path('app/public/' . $document->file_path);

        // Cek apakah file fisik benar-benar ada
        if (!file_exists($path)) {
            abort(404, 'File dokumen tidak ditemukan.');
        }

        // response()->file() ini adalah kunci ajaibnya!
        // Laravel otomatis akan menambahkan header "inline" agar browser melakukan preview
        return response()->file($path);
    }
}