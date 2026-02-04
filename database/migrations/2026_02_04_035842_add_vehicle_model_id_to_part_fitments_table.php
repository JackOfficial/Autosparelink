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
        Schema::table('part_fitments', function (Blueprint $table) {
          $table->foreignId('vehicle_model_id')
                  ->nullable()
                  ->after('variant_id')
                  ->constrained('vehicle_models')
                  ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('part_fitments', function (Blueprint $table) {
            $table->dropConstrainedForeignId('vehicle_model_id');
        });
    }
};
