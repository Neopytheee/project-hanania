<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // 🪄 Tambahkan ini di atas

class DocumentVerificationController extends Controller
{
    public function index()
    {
        $pendingDocuments = Document::with(['enrollment.customer', 'enrollment.travelPackage'])
            ->where('status', 'submitted')
            ->orderBy('uploaded_at', 'desc')
            ->get();

        return view('admin.documents.index', compact('pendingDocuments'));
    }

    public function approve(Request $request, Document $document)
    {
        if ($document->status !== 'submitted') {
            return back()->with('error', 'Dokumen tidak dalam status menunggu review.');
        }

        $document->update([
            'status' => 'approved',
            'reviewed_at' => now(),
            'reviewed_by' => $request->user()->id,
        ]);

        return back()->with('success', 'Dokumen '.strtoupper($document->document_type).' berhasil disetujui.');
    }

    public function reject(Request $request, Document $document)
    {
        if ($document->status !== 'submitted') {
            return back()->with('error', 'Dokumen tidak dalam status menunggu review.');
        }

        $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        $document->update([
            'status' => 'rejected',
            'rejection_reason' => $request->reason,
            'reviewed_at' => now(),
            'reviewed_by' => $request->user()->id,
        ]);

        return back()->with('success', 'Dokumen '.strtoupper($document->document_type).' ditolak. Jamaah harus mengunggah ulang.');
    }

    // 🔒 PERBAIKAN: Fungsi untuk memaksa preview file dari disk Private (Local)
    public function preview($id)
    {
        $document = Document::findOrFail($id);

        // Cek keberadaan file di disk local (storage/app/...)
        if (! Storage::disk('local')->exists($document->file_path)) {
            abort(404, 'File dokumen tidak ditemukan di server.');
        }

        // Kembalikan file menggunakan absolute path dari disk local
        return response()->file(Storage::disk('local')->path($document->file_path));
    }
}
