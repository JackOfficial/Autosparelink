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
        Schema::create('shippings', function (Blueprint $table) {
           $table->id(); // shipping_id

    // Relationship to orders table
    $table->foreignId('order_id')
          ->constrained('orders')
          ->cascadeOnDelete();

    // Shipping Details
    $table->string('carrier')->nullable();
    $table->string('tracking_number')->nullable();

    $table->timestamp('shipped_at')->nullable();
    $table->timestamp('delivered_at')->nullable();

    // Shipping Status
    $table->enum('status', [
        'pending',
        'shipped',
        'in_transit',
        'delivered',
        'failed'
    ])->default('pending');

    $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shippings');
    }
};
