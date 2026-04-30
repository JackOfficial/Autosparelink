<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('parts', function (Blueprint $table) {
            // price = What the shop wants to receive (already exists)
            // unit_price = Final price shown to the customer (Markup)
            $table->decimal('unit_price', 15, 2)->after('price')->default(0);
            $table->decimal('applied_rate', 5, 2)->after('unit_price')->default(0);
        });

        // Your OrderItem already has 'unit_price' and 'commission_amount'
        // We will just add 'shop_payout' to record the base price at time of sale
        Schema::table('order_items', function (Blueprint $table) {
            $table->decimal('shop_payout', 15, 2)->after('unit_price')->default(0);
        });
    }

    public function down(): void
    {
        Schema::table('parts', function (Blueprint $table) {
            $table->dropColumn(['unit_price', 'applied_rate']);
        });
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn('shop_payout');
        });
    }
};