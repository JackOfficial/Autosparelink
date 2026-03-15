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
        Schema::create('user_vehicles', function (Blueprint $table) {
            $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->string('make'); // e.g., Toyota
    $table->string('model'); // e.g., RAV4
    $table->year('year'); // e.g., 2018
    $table->string('engine')->nullable(); // e.g., 2.5L V6
    $table->string('vin')->nullable(); // Vehicle Identification Number
    $table->boolean('is_primary')->default(true);
    $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_vehicles');
    }
};
