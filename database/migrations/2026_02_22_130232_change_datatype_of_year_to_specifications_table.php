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
            // Change from YEAR(4) to string to support "YYYY-MM" or "Present"
            // We use length 10 to be safe (e.g., "2026-12" or "Present")
            $table->string('production_start', 10)->nullable()->change();
            $table->string('production_end', 10)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('specifications', function (Blueprint $table) {
            // Reverting back to YEAR(4) 
            // Warning: This will truncate "2024-05" to "2024" and "Present" to "0000"
            $table->year('production_start')->nullable()->change();
            $table->year('production_end')->nullable()->change();
        });
    }
};
