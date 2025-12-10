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
            $table->string('chassis_code')->nullable()->after('name');
            $table->string('model_code')->nullable()->after('chassis_code');
            $table->string('steering_position')->nullable()->after('drive_type');
            $table->string('trim_level')->nullable()->after('steering_position');
            $table->string('color')->nullable()->after('trim_level');
            $table->string('production_start')->nullable()->after('color');
            $table->string('production_end')->nullable()->after('production_start');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('variants', function (Blueprint $table) {
            $table->dropColumn([
                'chassis_code',
                'model_code',
                'steering_position',
                'trim_level',
                'color',
                'production_start',
                'production_end'
            ]);
        });
    }
};
