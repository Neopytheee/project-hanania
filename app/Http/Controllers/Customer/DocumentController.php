<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function index(Enrollment $enrollment)
    {
        if ($enrollment->customer_id !== auth()->user()->customer->id) {
            abort(403, 'Akses ditolak.');
        }

        return view('customer.documents.index', compact('enrollment'));
    }

    public function store(Request $request, Enrollment $enrollment)
    {
        if ($enrollment->customer_id !== $request->user()->customer->id) {
            abort(403, 'Akses ditolak.');
        }

        $request->validate([
            'document_type' => 'required|in:ktp,kk,passport_biodata,passport_endorsement,photo,other',
            'file_path' => 'required|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        // 🔒 PERBAIKAN: Simpan ke disk 'local' (Private), bukan 'public'
        $file = $request->file('file_path');
        $path = $file->store('dokumen_jamaah/'.$enrollment->id, 'local');

        $existingDoc = Document::where('enrollment_id', $enrollment->id)
            ->where('document_type', $request->document_type)
            ->first();

        // 🔒 PERBAIKAN: Hapus dari disk 'local'
        if ($existingDoc && Storage::disk('local')->exists($existingDoc->file_path)) {
            Storage::disk('local')->delete($existingDoc->file_path);
        }

        Document::updateOrCreate(
            ['enrollment_id' => $enrollment->id, 'document_type' => $request->document_type],
            [
                'file_path' => $path,
                'status' => 'submitted',
                'rejection_reason' => null,
                'uploaded_at' => now(),
            ]
        );

        return back()->with('success', 'Dokumen '.strtoupper($request->document_type).' berhasil diunggah dan sedang direview.');
    }

    public function preview($id)
    {
        $document = Document::with('enrollment')->findOrFail($id);

        if (! $document->enrollment || $document->enrollment->customer_id !== auth()->user()->customer->id) {
            abort(403, 'Akses ditolak. Dokumen ini bukan milik Anda.');
        }

        // 🔒 PERBAIKAN: Cek dan ambil path absolut file dari disk 'local'
        if (! Storage::disk('local')->exists($document->file_path)) {
            abort(404, 'File dokumen tidak ditemukan.');
        }

        return response()->file(Storage::disk('local')->path($document->file_path));
    }
}
