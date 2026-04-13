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
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();
            
            // Link to the wallet
            $table->foreignId('wallet_id')->constrained()->onDelete('cascade');

            /**
             * type: 
             * 'credit' - Money coming in (from a sale)
             * 'debit'  - Money going out (withdrawal/payout)
             */
            $table->enum('type', ['credit', 'debit'])->default('credit');

            // The actual amount added to or removed from the balance
            $table->decimal('amount', 15, 2);

            // The admin service fee deducted (if credit)
            $table->decimal('service_fee', 15, 2)->default(0.00);
            
            // The percentage used for this specific transaction (e.g., 5.00)
            $table->decimal('fee_percentage', 5, 2)->default(0.00);

            /**
             * Morphic fields to link to an Order, a PayoutRequest, or an SMM service.
             * This allows $transaction->reference to return the specific Order object.
             */
            $table->nullableMorphs('reference');

            $table->string('description')->nullable();
            
            /**
             * status:
             * 'pending'   - Transaction is being processed
             * 'completed' - Money is officially in/out of the wallet
             * 'failed'    - Transaction was rejected or cancelled
             */
            $table->string('status')->default('completed');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
    }
};