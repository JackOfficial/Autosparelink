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
        Schema::table('specifications', function (Blueprint $table) {
            $table->foreignId('engine_displacement_id')
                  ->nullable()
                  ->after('engine_type_id')
                  ->constrained('engine_displacements')
                  ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('specifications', function (Blueprint $table) {
            $table->dropForeign(['engine_displacement_id']);
            $table->dropColumn('engine_displacement_id');
        });
    }
};
