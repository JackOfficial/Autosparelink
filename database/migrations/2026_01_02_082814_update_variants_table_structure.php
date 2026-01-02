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
        Schema::table('variants', function (Blueprint $table) {
            $table->dropForeign(['body_type_id']);
            $table->dropForeign(['engine_type_id']);
            $table->dropForeign(['transmission_type_id']);
            $table->dropForeign(['drive_type_id']);

            $table->dropColumn([
                'body_type_id',
                'engine_type_id',
                'transmission_type_id',
                'drive_type_id',
                'steering_position',
                'color',
                'production_start',
                'production_end',
                'fuel_capacity',
                'seats',
                'doors',
                'horsepower',
                'torque',
                'fuel_efficiency'
            ]);

            // Make 'name' nullable if needed
            $table->string('name')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('variants', function (Blueprint $table) {
              // Restore previous columns
            $table->foreignId('body_type_id')->constrained('body_types')->cascadeOnDelete();
            $table->foreignId('engine_type_id')->constrained('engine_types')->cascadeOnDelete();
            $table->foreignId('transmission_type_id')->constrained('transmission_types')->cascadeOnDelete();
            $table->foreignId('drive_type_id')->nullable()->constrained('drive_types')->cascadeOnDelete();

            $table->string('steering_position')->nullable();
            $table->string('color')->nullable();
            $table->year('production_start')->nullable();
            $table->year('production_end')->nullable();
            $table->string('fuel_capacity')->nullable();
            $table->integer('seats')->nullable();
            $table->integer('doors')->nullable();
            $table->string('horsepower')->nullable();
            $table->string('torque')->nullable();
            $table->string('fuel_efficiency')->nullable();

            $table->string('name')->nullable(false)->change();
        });
    }
};
