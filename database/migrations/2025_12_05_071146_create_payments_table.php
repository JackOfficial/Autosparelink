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
        Schema::create('payments', function (Blueprint $table) {
           $table->id(); // payment_id

    // Foreign Key → orders table
    $table->foreignId('order_id')
          ->constrained('orders')
          ->cascadeOnDelete();

    // Payment details
    $table->decimal('amount', 10, 2);
    $table->string('method'); // e.g., card, mobile_money, cash, flutterwave
    $table->string('transaction_reference')->nullable();
    
    // Payment status (standard for gateways)
    $table->enum('status', [
        'pending',
        'processing',
        'successful',
        'failed',
        'refunded'
    ])->default('pending');

    $table->timestamp('paid_at')->nullable();

    $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
