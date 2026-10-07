<?php

namespace App\Services;

use App\Models\Document;
use App\Models\Enrollment;
use App\Models\User;

class DocumentService
{
    public function upload(
        Enrollment $enrollment,
        string $documentType,
        string $filePath
    ): Document {
        return Document::updateOrCreate(
            [
                'enrollment_id' => $enrollment->id,
                'document_type' => $documentType,
            ],
            [
                'file_path' => $filePath,
                'status' => 'submitted',
                'rejection_reason' => null,
                'uploaded_at' => now(),
            ]
        );
    }

    public function approve(
        Document $document,
        User $admin
    ): Document {
        $document->update([
            'status' => 'approved',
            'reviewed_at' => now(),
            'reviewed_by' => $admin->id,
            'rejection_reason' => null,
        ]);

        return $document->fresh();
    }

    public function reject(
        Document $document,
        User $admin,
        string $reason
    ): Document {
        $document->update([
            'status' => 'rejected',
            'reviewed_at' => now(),
            'reviewed_by' => $admin->id,
            'rejection_reason' => $reason,
        ]);

        return $document->fresh();
    }

    public function isComplete(Enrollment $enrollment): bool
    {
        // 🔒 PERBAIKAN LOGIKA: Sesuaikan nama tipe dokumen dengan ENUM di tabel database
        $requiredDocuments = [
            'ktp',
            'kk',
            'passport_biodata',      // Menggantikan 'passport'
            'passport_endorsement',  // Menggantikan 'passport'
            'photo',
        ];

        return $enrollment
            ->documents()
            ->whereIn('document_type', $requiredDocuments)
            ->where('status', 'approved')
            ->distinct('document_type')
            ->count('document_type') === count($requiredDocuments);
    }
}
