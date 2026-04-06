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
        // 1. Update Order Items for Shop Attribution
       Schema::table('order_items', function (Blueprint $table) {
            if (!Schema::hasColumn('order_items', 'shop_id')) {
                $table->foreignId('shop_id')->after('part_id')->constrained();
            }
            if (!Schema::hasColumn('order_items', 'part_name')) {
                $table->string('part_name')->after('unit_price');
            }
            if (!Schema::hasColumn('order_items', 'status')) {
                $table->enum('status', ['pending', 'packed', 'ready_for_pickup', 'collected', 'at_hub'])
                      ->default('pending')
                      ->after('part_name');
            }
        });

        // 2. Update Shipping for Global Fulfillment (Scenario A)
        Schema::table('shippings', function (Blueprint $table) {
            if (!Schema::hasColumn('shippings', 'shipping_method')) {
                $table->string('shipping_method')->default('Standard')->after('carrier');
            }
            if (!Schema::hasColumn('shippings', 'shipping_cost')) {
                $table->decimal('shipping_cost', 10, 2)->default(0.00)->after('shipping_method');
            }
            if (!Schema::hasColumn('shippings', 'notes')) {
                $table->text('notes')->nullable()->after('status');
            }
        });

        // 3. Update Tickets for Order Linking
        Schema::table('tickets', function (Blueprint $table) {
            if (!Schema::hasColumn('tickets', 'order_id')) {
                $table->foreignId('order_id')->nullable()->after('user_id')->constrained();
            }
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse the changes if needed
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropForeign(['shop_id']);
            $table->dropColumn(['shop_id', 'part_name', 'status']);
        });
        
        Schema::table('shippings', function (Blueprint $table) {
            $table->dropColumn(['shipping_method', 'shipping_cost', 'notes']);
        });

        Schema::table('tickets', function (Blueprint $table) {
            $table->dropForeign(['order_id']);
            $table->dropColumn('order_id');
        });
    }
};
