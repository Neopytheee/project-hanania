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
        Schema::table('payment_transactions', function (Blueprint $table) {
            $table->decimal('fee_amount', 15, 2)->default(0.00);
            $table->decimal('fee_rate', 15, 6)->default(0.000000);
            $table->decimal('net_amount', 15, 2)->default(0.00);
            $table->string('midtrans_channel')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payment_transactions', function (Blueprint $table) {
            $table->dropColumn(['fee_amount', 'fee_rate', 'net_amount', 'midtrans_channel']);
        });
    }
};
