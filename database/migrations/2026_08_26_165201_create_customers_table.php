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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();

            /*
             * Satu akun user customer = satu data customer.
             */
            $table->foreignId('user_id')
                ->unique()
                ->constrained('users')
                ->restrictOnDelete();

            $table->string('customer_number', 50)->unique();

            $table->string('name');

            $table->string('nik', 32)
                ->nullable()
                ->unique();

            $table->string('phone', 30);

            $table->string('email')->nullable();

            $table->date('birth_date')->nullable();

            $table->enum('gender', [
                'male',
                'female',
            ])->nullable();

            $table->text('address')->nullable();

            $table->string('emergency_contact_name')->nullable();

            $table->string('emergency_contact_phone', 30)
                ->nullable();

            $table->string('profile_image')->nullable();

            // 🏦 TAMBAHAN BARU: Informasi Rekening Bank Jamaah untuk Keperluan Refund
            $table->string('bank_name')->nullable();           // Contoh: BCA, Mandiri, BSI
            $table->string('bank_account_number')->nullable(); // Nomor Rekening
            $table->string('bank_account_name')->nullable();   // Nama Pemilik Rekening

            $table->enum('status', [
                'active',
                'inactive',
            ])->default('active');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};