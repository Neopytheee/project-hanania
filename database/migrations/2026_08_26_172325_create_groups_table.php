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
        Schema::create('groups', function (Blueprint $table) {
            $table->id();

            $table->foreignId('departure_id')
                ->nullable()
                ->constrained('departures')
                ->restrictOnDelete();

            $table->string('code', 50);

            $table->string('name');

            $table->unsignedInteger('capacity');

            $table->enum('status', [
                'draft',
                'filling',
                'full',
                'finalized',
                'ready',
                'departed',
                'completed',
                'cancelled',
            ])->default('draft');

            $table->timestamp('finalized_at')->nullable();

            $table->timestamps();

            /*
             * Dalam satu departure,
             * kode group harus unik.
             */
            $table->unique([
                'departure_id',
                'code',
            ]);

            $table->index([
                'departure_id',
                'status',
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('groups');
    }
};