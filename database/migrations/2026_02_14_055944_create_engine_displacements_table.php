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
        Schema::create('engine_displacements', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // e.g., 1.8L, 2.0L
            $table->tinyInteger('status')->default(1); // active/inactive
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('engine_displacements');
    }
};
