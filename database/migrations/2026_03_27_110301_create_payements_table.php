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
        Schema::create('payements', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->enum('status',['pending','paid','processing','failed','cancelled','refunded','partialy_refunded'])->default('pending');
            $table->enum('method',['stripe','paypal']);
            $table->float('amount');
            $table->integer('transaction_id');
            $table->foreignId('order_id')->constrained('orders')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payements');
    }
};
