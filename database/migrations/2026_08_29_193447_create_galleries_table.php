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
    Schema::create('galleries', function (Blueprint $table) {
        $table->id();
        
        // RELASI: Foto ini masuk ke kategori ID berapa?
        $table->foreignId('gallery_category_id')->constrained('gallery_categories')->cascadeOnDelete();
        
        $table->string('title'); // Judul foto
        $table->string('image_path'); // Alamat file
        $table->text('description')->nullable(); 
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('galleries');
    }
};
