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
        Schema::create('destinations', function (Blueprint $table) {
            $table->id();
            $table->string('region_name'); // e.g., Europe, North America, Japan, GCC
            $table->string('region_code')->nullable(); // e.g., EUR, USA, JPN
            $table->timestamps();
        });

         // You also need a pivot table to link them to your Variants
        Schema::create('destination_variant', function (Blueprint $table) {
           $table->id();
           $table->foreignId('variant_id')->constrained()->onDelete('cascade');
           $table->foreignId('destination_id')->constrained()->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('destinations');
    }
};
