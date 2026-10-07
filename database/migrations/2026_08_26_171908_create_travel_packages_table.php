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
        Schema::create('travel_packages', function (Blueprint $table) {
            $table->id();

            // Kolom penentu untuk Service membuat kode otomatis (UMR/HJI)
            $table->enum('category', ['UMROH', 'HAJI'])->default('UMROH');

            $table->string('code', 50)->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->text('facilities')->nullable();

            // Kolom tambahan untuk Quick Facts di halaman Customer
            $table->string('airline')->nullable();
            $table->string('hotel_mekkah')->nullable();

            $table->string('image')->nullable();

            /*
             * Harga yang ditampilkan kepada calon jamaah.
             * Ini HANYA harga estimasi.
             */
            $table->decimal('estimated_price', 15, 2);

            $table->unsignedSmallInteger('duration_days')->nullable();

            $table->enum('status', [
                'draft',
                'active',
                'inactive',
                'archived',
            ])->default('draft');

            $table->timestamps();

            $table->index([
                'status',
                'estimated_price',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('travel_packages');
    }
};
