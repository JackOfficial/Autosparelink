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
        // 1. Update Orders Table
        Schema::table('orders', function (Blueprint $table) {
            $table->boolean('is_guest')->default(false)->after('user_id');
        });

        // 2. Update Order Items Table
        Schema::table('order_items', function (Blueprint $table) {
            // Decimal 12,2 is safer for high-value currency totals
            $table->decimal('commission_amount', 12, 2)->default(0)->after('unit_price');
        });

        // 3. Update Shipping Table
        Schema::table('shippings', function (Blueprint $table) {
            // Nullable allows for backward compatibility and single-vendor orders
            $table->foreignId('shop_id')->nullable()->constrained()->onDelete('set null')->after('order_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('is_guest');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn('commission_amount');
        });

        Schema::table('shippings', function (Blueprint $table) {
            $table->dropForeign(['shop_id']);
            $table->dropColumn('shop_id');
        });
    }
};
