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
        Schema::create('part_brands', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // e.g., Bosch, Denso, Aisin
            $table->string('country')->nullable(); // optional: Japan, Germany, USA
            $table->enum('type', ['OEM', 'Aftermarket'])->default('Aftermarket'); 
                // OEM = original suppliers
                // Aftermarket = replacement brands
            $table->text('description')->nullable(); // optional brand info
            $table->string('logo')->nullable(); // store brand logo path
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('part_brands');
    }
};
