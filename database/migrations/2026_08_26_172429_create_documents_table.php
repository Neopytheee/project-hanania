<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();

            $table->foreignId('enrollment_id')
                ->constrained('enrollments')
                ->restrictOnDelete();

            $table->enum('document_type', [
                'ktp',
                'kk',
                'passport_biodata',
                'passport_endorsement',
                'photo',
                'other',
            ]);

            $table->string('file_path');

            $table->enum('status', [
                'submitted',
                'under_review',
                'approved',
                'rejected',
            ])->default('submitted');

            $table->text('rejection_reason')->nullable();

            $table->timestamp('uploaded_at')
                ->useCurrent();

            $table->timestamp('reviewed_at')->nullable();

            $table->foreignId('reviewed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            /*
             * Satu jenis dokumen aktif untuk satu enrollment.
             */
            $table->unique([
                'enrollment_id',
                'document_type',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};