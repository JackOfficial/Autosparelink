<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payouts', function (Blueprint $table) {
            // Adds internal tracking reference for InTouch
            $table->string('reference')->nullable()->unique()->after('account_details');

            // Stores the ID returned by the bank/telco
            $table->string('gateway_transaction_id')->nullable()->after('reference');

            // Currency field (defaults to RWF)
            $table->string('currency')->default('RWF')->after('amount');

            // Log for API errors so we know WHY a payout failed
            $table->text('error_log')->nullable()->after('admin_note');

            // Timestamp for when the money actually moved
            $table->timestamp('processed_at')->nullable()->after('updated_at');
        });
    }

    public function down(): void
    {
        Schema::table('payouts', function (Blueprint $table) {
            $table->dropColumn([
                'reference', 
                'gateway_transaction_id', 
                'currency', 
                'error_log', 
                'processed_at'
            ]);
        });
    }
};