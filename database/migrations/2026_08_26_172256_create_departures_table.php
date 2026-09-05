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
        Schema::create('departures', function (Blueprint $table) {
            $table->id();

            $table->string('code', 50)->unique();

            /*
             * Departure untuk paket tertentu.
             */
            $table->foreignId('travel_package_id')
                ->constrained('travel_packages')
                ->restrictOnDelete();

            $table->string('name');

            $table->date('departure_date');

            /*
             * Kapasitas total departure.
             */
            $table->unsignedInteger('quota');

            /*
             * Harga estimasi pada departure.
             */
            $table->decimal('estimated_price', 15, 2);

            /*
             * Harga final departure.
             */
            $table->decimal('final_price', 15, 2)->nullable();

            $table->enum('status', [
                'draft',
                'open',
                'filling',
                'full',
                'closed',
                'ready',
                'departed',
                'completed',
                'cancelled',
            ])->default('draft');

            // ==========================================
            // TAMBAHAN: Kolom Manasik & Itinerary
            // ==========================================
            $table->datetime('manasik_date')->nullable();
            $table->string('manasik_location')->nullable();
            $table->string('itinerary_file')->nullable();

            $table->timestamp('finalized_at')->nullable();

            $table->foreignId('finalized_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index([
                'travel_package_id',
                'departure_date',
            ]);

            $table->index([
                'status',
                'departure_date',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('departures');
    }
};