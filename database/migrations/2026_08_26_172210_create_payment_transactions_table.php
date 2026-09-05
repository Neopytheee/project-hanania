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
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('payment_plan_id')
                ->constrained('payment_plans')
                ->restrictOnDelete();

            $table->string('transaction_number', 50)->unique();

            /*
             * Jenis pembayaran.
             * DITAMBAHKAN: 'refund' untuk pencairan/penarikan dana
             */
            $table->enum('type', [
                'initial_deposit',
                'monthly_payment',
                'additional_payment',
                'final_payment',
                'adjustment',
                'refund', // 👈 INI YANG KITA TAMBAHKAN
            ])->default('monthly_payment');

            $table->decimal('amount', 15, 2);

            $table->enum('payment_method', [
                'midtrans',
                'manual_transfer',
                'bank_transfer',
                'cash',
                'manual',
                'other',
            ])->default('bank_transfer');

            /*
             * Nomor referensi dari transaksi pembayaran
             * jika tersedia.
             */
            $table->string('reference_number', 100)
                ->nullable()
                ->unique();

            /*
             * Bukti transfer.
             */
            $table->string('proof_file')->nullable();

            $table->enum('status', [
                'pending',
                'verified',
                'rejected',
                'expired',
            ])->default('pending');

            $table->timestamp('paid_at')->nullable();

            $table->timestamp('verified_at')->nullable();

            $table->foreignId('verified_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->text('rejection_reason')->nullable();

            $table->timestamps();

            $table->index([
                'payment_plan_id',
                'status',
            ]);

            $table->index([
                'type',
                'status',
            ]);

            $table->index('paid_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_transactions');
    }
};