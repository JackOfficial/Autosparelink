<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('part_fitments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('part_id')->constrained()->onDelete('cascade');
            $table->foreignId('variant_id')->constrained()->onDelete('cascade');

            $table->foreignId('vehicle_model_id')->constrained()->onDelete('cascade');

            $table->year('start_year')->nullable();
            $table->year('end_year')->nullable();

            $table->enum('status', ['active', 'inactive'])->default('active');

            $table->timestamps();

            $table->unique(['part_id', 'variant_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('part_fitments');
    }
};
