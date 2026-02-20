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
            $table->string('trim_level')->nullable()->after('vehicle_model_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('specifications', function (Blueprint $table) {
            $table->dropColumn('trim_level');
        });
    }
};
