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
        Schema::create('commissions', function (Blueprint $table) {
            $table->id();

            /**
             * type: Determines the scope of the commission.
             * - 'global': Applies to all shops.
             * - 'shop': Applies only to a specific shop.
             */
            $table->string('type')->default('global');

            /**
             * shop_id: Nullable because 'global' type won't need it.
             * If type is 'shop', this links to the specific vendor.
             */
            $table->foreignId('shop_id')->nullable()->constrained()->onDelete('cascade');

            /**
             * rate: The percentage to deduct.
             * Example: 10.00 means 10%. 
             * Using 5 total digits with 2 decimal places (999.99 max).
             */
            $table->decimal('rate', 5, 2)->default(10.00);

            $table->string('description')->nullable();
            
            /**
             * is_active: Allows the Admin to toggle specific 
             * commission rules without deleting them.
             */
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            // Indexing for faster lookups when calculating earnings
            $table->index(['type', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commissions');
    }
};