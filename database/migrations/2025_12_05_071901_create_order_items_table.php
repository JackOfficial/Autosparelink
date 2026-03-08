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
        Schema::create('order_items', function (Blueprint $table) {
          $table->id(); // order_item_id

    // Foreign Keys
    $table->foreignId('order_id')
          ->constrained('orders')
          ->cascadeOnDelete();

    $table->foreignId('part_id')
          ->constrained('parts')
          ->cascadeOnDelete();

    // Order Item Details
    $table->integer('quantity')->default(1);
    $table->decimal('unit_price', 10, 2);

    $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
