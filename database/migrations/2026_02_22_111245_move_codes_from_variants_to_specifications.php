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
       // 1. Add columns to specifications
        Schema::table('specifications', function (Blueprint $table) {
            $table->string('chassis_code')->nullable()->after('id');
            $table->string('model_code')->nullable()->after('chassis_code');
        });

        // 2. Optional: Script to move data if they are linked by a foreign key
        // This assumes 'specifications' has a 'variant_id' or vice versa.
        // If this is a fresh project, you can skip this part.

        // 3. Remove columns from variants
        Schema::table('variants', function (Blueprint $table) {
            $table->dropColumn(['chassis_code', 'model_code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       // Reverse the process
        Schema::table('variants', function (Blueprint $table) {
            $table->string('chassis_code')->nullable();
            $table->string('model_code')->nullable();
        });

        Schema::table('specifications', function (Blueprint $table) {
            $table->dropColumn(['chassis_code', 'model_code']);
        });
    }
};
