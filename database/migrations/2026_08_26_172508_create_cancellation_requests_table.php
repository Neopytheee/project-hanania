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
        Schema::create('cancellation_requests', function (Blueprint $table) {
            $table->id();

            $table->foreignId('enrollment_id')
                ->constrained('enrollments')
                ->restrictOnDelete();

            $table->enum('cancellation_type', [
                'voluntary_withdrawal',
                'medical',
                'death',
                'force_majeure',
                'other',
            ]);

            $table->text('reason')->nullable();

            $table->enum('status', [
                'requested',
                'under_review',
                'approved',
                'rejected',
                'refund_processing',
                'refunded',
                'credited',
            ])->default('requested');

            // Dokumen dari Customer
            $table->string('supporting_document')->nullable();
            
            // Dokumen dari Admin (TAMBAHAN BARU)
            $table->string('admin_acc_document')->nullable();
            $table->string('admin_transfer_proof')->nullable();

            /*
             * Biaya pengunduran diri / administrasi.
             */
            $table->decimal('penalty_amount', 15, 2)
                ->default(0);

            /*
             * Jumlah yang benar-benar dikembalikan.
             */
            $table->decimal('refund_amount', 15, 2)
                ->default(0);

            /*
             * Jika kelebihan dana diberikan sebagai credit.
             */
            $table->decimal('credit_amount', 15, 2)
                ->default(0);

            $table->timestamp('requested_at')
                ->useCurrent();

            $table->timestamp('reviewed_at')->nullable();

            $table->foreignId('reviewed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index([
                'enrollment_id',
                'status',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cancellation_requests');
    }
};