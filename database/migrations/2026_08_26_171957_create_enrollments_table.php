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
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();

            $table->string('enrollment_number', 50)->unique();

            /*
             * Kode unik jamaah.
             * Digunakan saat melakukan pembayaran.
             */
            $table->string('unique_code', 30)->unique();

            $table->foreignId('customer_id')
                ->constrained('customers')
                ->restrictOnDelete();

            /*
             * ---------------------------------------------------------
             * TAMBAHAN KOLOM: Informasi Penumpang / Jamaah yang Berangkat
             * ---------------------------------------------------------
             */
            $table->string('passenger_name')->nullable();
            $table->string('relationship')->default('Diri Sendiri');

            /*
             * Paket yang dipilih jamaah sejak awal.
             */
            $table->foreignId('travel_package_id')
                ->constrained('travel_packages')
                ->restrictOnDelete();

            /*
             * Harga estimasi pada saat enrollment dibuat.
             * Tidak berubah walaupun harga paket katalog berubah.
             */
            $table->decimal('estimated_price_snapshot', 15, 2);

            /*
             * Harga final yang ditetapkan kemudian.
             */
            $table->decimal('final_price', 15, 2)->nullable();

            /*
             * Lifecycle enrollment.
             */
            $table->enum('status', [
                'enrolled',
                'saving',
                'funds_sufficient',
                'waiting_schedule',
                'scheduled',
                'price_confirmed',
                'payment_due',
                'overdue',
                'fully_paid',
                'ready',
                'departed',
                'completed',
                'cancelled',
            ])->default('enrolled');

            $table->timestamp('enrolled_at')
                ->useCurrent();

            $table->timestamp('funds_sufficient_at')
                ->nullable();

            $table->timestamp('scheduled_at')
                ->nullable();

            $table->timestamp('price_confirmed_at')
                ->nullable();

            $table->timestamp('completed_at')
                ->nullable();

            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index([
                'customer_id',
                'status',
            ]);

            $table->index([
                'travel_package_id',
                'status',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};