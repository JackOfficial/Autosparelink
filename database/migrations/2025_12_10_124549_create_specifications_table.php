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
        Schema::create('specifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_model_id')->nullable()->constrained('vehicle_models')->cascadeOnDelete();
            $table->foreignId('variant_id')->nullable()->constrained('variants')->cascadeOnDelete();
            $table->foreignId('body_type_id')->constrained('body_types')->cascadeOnDelete();
            $table->foreignId('engine_type_id')->constrained('engine_types')->cascadeOnDelete();
            $table->foreignId('transmission_type_id')->constrained('transmission_types')->cascadeOnDelete();
            $table->foreignId('drive_type_id')->nullable()->constrained('drive_types')->cascadeOnDelete();

            $table->year('production_start')->nullable();
            $table->year('production_end')->nullable();
            $table->string('horsepower')->nullable();
            $table->string('torque')->nullable();
            $table->string('fuel_capacity')->nullable();
            $table->integer('seats')->nullable();
            $table->integer('doors')->nullable();
            $table->string('fuel_efficiency')->nullable();
            $table->string('steering_position')->nullable();
            $table->string('color')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('specifications');
    }
};
