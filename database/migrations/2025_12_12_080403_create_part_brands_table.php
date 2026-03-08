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
            $table->string('country')->nullable();
            $table->enum('type', ['OEM', 'Aftermarket'])->default('Aftermarket'); 
            $table->text('description')->nullable();
            $table->string('logo')->nullable();
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
