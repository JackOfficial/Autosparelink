<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('commissions', function (Blueprint $table) {
            // 1. Drop the manual index you created in the first migration
            // Laravel names this: table_column1_column2_index
            $table->dropIndex(['type', 'is_active']);

            // 2. Wrap foreign key drop in a check or use raw SQL to be safe
            // This bypasses naming issues that cause "Can't DROP FOREIGN KEY"
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            
            $table->dropColumn(['shop_id', 'type']);
            
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        });
    }

    public function down(): void
    {
        Schema::table('commissions', function (Blueprint $table) {
            $table->string('type')->default('global');
            $table->foreignId('shop_id')->nullable()->constrained()->onDelete('cascade');
            $table->index(['type', 'is_active']);
        });
    }
};