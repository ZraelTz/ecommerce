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
        Schema::create('transactions', function (Blueprint $table) {
            // 'order_id',
            // 'amount',
            // 'invoice',
            // 'payment_method',
            // 'payment_status',
            // 'order_status'
            $table->id();
            $table->string('order_id');
            $table->double('amount');
            $table->double('invoice');
            $table->string('payment_method');
            $table->tinyInteger('payment_status');
            $table->tinyInteger('order_status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
