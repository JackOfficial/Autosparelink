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
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            // Link to the shop
            $table->foreignId('shop_id')->constrained()->onDelete('cascade');
            
            /**
             * balance: Available for withdrawal
             * pending_balance: Money from recent sales held until delivery is confirmed
             * withdrawn_balance: Total amount ever paid out to the shop (useful for reporting)
             */
            $table->decimal('balance', 15, 2)->default(0.00);
            $table->decimal('pending_balance', 15, 2)->default(0.00);
            $table->decimal('withdrawn_balance', 15, 2)->default(0.00);
            
            $table->string('currency')->default('RWF');
            
            // To prevent double-processing or errors during high-traffic sales
            $table->timestamp('last_transaction_at')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallets');
    }
};