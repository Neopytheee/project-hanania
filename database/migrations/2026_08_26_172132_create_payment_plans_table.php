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
        Schema::create('payment_plans', function (Blueprint $table) {
            $table->id();

            /*
             * Satu enrollment memiliki satu payment plan.
             */
            $table->foreignId('enrollment_id')
                ->unique()
                ->constrained('enrollments')
                ->restrictOnDelete();

            /*
             * Snapshot aturan tabungan Hanania
             * ketika jamaah mendaftar.
             */
            $table->decimal('minimum_initial_payment', 15, 2);

            $table->decimal('minimum_monthly_payment', 15, 2);

            /*
             * Hanania:
             * Setoran bulanan paling lambat tanggal 25.
             */
            $table->unsignedTinyInteger('monthly_due_day')
                ->default(25);

            /*
             * Target berdasarkan estimasi harga paket.
             */
            $table->decimal('estimated_target_amount', 15, 2);

            /*
             * Target setelah harga final ditentukan.
             */
            $table->decimal('final_target_amount', 15, 2)->nullable();

            /*
             * Deadline pelunasan setelah harga final/jadwal.
             */
            $table->date('due_date')->nullable();

            $table->enum('status', [
                'active',
                'finalized',
                'overdue',
                'fully_paid',
                'cancelled',
            ])->default('active');

            $table->timestamp('finalized_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_plans');
    }
};