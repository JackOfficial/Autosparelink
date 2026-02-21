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
            // 1. Remove the old unique constraint and index first
            $table->dropUnique(['part_id', 'variant_id']);
            $table->dropIndex('fitment_search_index');

            // 2. Drop the unnecessary columns
            $table->dropColumn(['variant_id', 'vehicle_model_id', 'start_year', 'end_year']);

            // 3. Add the new specification_id column
            $table->foreignId('specification_id')
                  ->after('part_id')
                  ->constrained()
                  ->onDelete('cascade');

            // 4. Add a new unique constraint to prevent duplicate fitments
            $table->unique(['part_id', 'specification_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('part_fitments', function (Blueprint $table) {
            $table->dropUnique(['part_id', 'specification_id']);
            $table->dropForeign(['specification_id']);
            $table->dropColumn('specification_id');

            // Revert back to old structure
            $table->foreignId('variant_id')->constrained()->onDelete('cascade');
            $table->foreignId('vehicle_model_id')->constrained()->onDelete('cascade');
            $table->year('start_year')->nullable();
            $table->year('end_year')->nullable();
            
            $table->unique(['part_id', 'variant_id']);
            $table->index(['vehicle_model_id', 'start_year', 'end_year'], 'fitment_search_index');
        });
    }
};
