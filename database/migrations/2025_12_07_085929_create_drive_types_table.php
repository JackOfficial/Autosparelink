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
        Schema::create('drive_types', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('name'); // e.g., FWD, RWD, AWD
            $table->text('description')->nullable(); // optional description
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drive_types');
    }
};
