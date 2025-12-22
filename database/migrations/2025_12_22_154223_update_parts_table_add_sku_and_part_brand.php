<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('parts', function (Blueprint $table) {

            /* ADD SKU */
            if (!Schema::hasColumn('parts', 'sku')) {
                $table->string('sku')->unique()->after('id');
            }

            /* ADD PART BRAND RELATION */
            if (!Schema::hasColumn('parts', 'part_brand_id')) {
                $table->foreignId('part_brand_id')
                      ->after('category_id')
                      ->constrained('part_brands')
                      ->cascadeOnDelete();
            }

            /* ADD OEM NUMBER (OPTIONAL) */
            if (!Schema::hasColumn('parts', 'oem_number')) {
                $table->string('oem_number')->nullable()->after('part_number');
            }

            /* REMOVE OLD brand_id IF IT EXISTS */
            if (Schema::hasColumn('parts', 'brand_id')) {
                $table->dropForeign(['brand_id']);
                $table->dropColumn('brand_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('parts', function (Blueprint $table) {

            if (Schema::hasColumn('parts', 'sku')) {
                $table->dropColumn('sku');
            }

            if (Schema::hasColumn('parts', 'oem_number')) {
                $table->dropColumn('oem_number');
            }

            if (Schema::hasColumn('parts', 'part_brand_id')) {
                $table->dropForeign(['part_brand_id']);
                $table->dropColumn('part_brand_id');
            }

            /* RESTORE brand_id IF YOU EVER ROLLBACK */
            if (!Schema::hasColumn('parts', 'brand_id')) {
                $table->foreignId('brand_id')
                      ->nullable()
                      ->constrained('brands')
                      ->nullOnDelete();
            }
        });
    }
};
