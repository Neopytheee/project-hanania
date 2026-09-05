<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\Request;

class DocumentVerificationController extends Controller
{
    // Menampilkan daftar dokumen yang butuh direview (status: submitted)
    public function index()
    {
        // Ambil dokumen yang baru diupload beserta data jamaah dan paketnya
        $pendingDocuments = Document::with(['enrollment.customer', 'enrollment.travelPackage'])
            ->where('status', 'submitted')
            ->orderBy('uploaded_at', 'desc')
            ->get();

        return view('admin.documents.index', compact('pendingDocuments'));
    }

    // Menyetujui dokumen
    public function approve(Request $request, Document $document)
    {
        if ($document->status !== 'submitted') {
            return back()->with('error', 'Dokumen tidak dalam status menunggu review.');
        }

        $document->update([
            'status'      => 'approved',
            'reviewed_at' => now(),
            'reviewed_by' => $request->user()->id,
        ]);

        return back()->with('success', 'Dokumen ' . strtoupper($document->document_type) . ' berhasil disetujui.');
    }

    // Menolak dokumen dengan alasan
    public function reject(Request $request, Document $document)
    {
        if ($document->status !== 'submitted') {
            return back()->with('error', 'Dokumen tidak dalam status menunggu review.');
        }

        $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        $document->update([
            'status'           => 'rejected',
            'rejection_reason' => $request->reason,
            'reviewed_at'      => now(),
            'reviewed_by'      => $request->user()->id,
        ]);

        return back()->with('success', 'Dokumen ' . strtoupper($document->document_type) . ' ditolak. Jamaah harus mengunggah ulang.');
    }

    // Fungsi untuk memaksa preview file di browser
    public function preview($id)
    {
        $document = \App\Models\Document::findOrFail($id);
        $path = storage_path('app/public/' . $document->file_path);

        if (!file_exists($path)) {
            abort(404, 'File dokumen tidak ditemukan di server.');
        }

        // response()->file() akan memaksa browser untuk melakukan PREVIEW, bukan download
        return response()->file($path);
    }
}