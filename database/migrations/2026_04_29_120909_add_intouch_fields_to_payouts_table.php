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
        Schema::table('payouts', function (Blueprint $table) {
            // Adds the internal unique reference for the API handshake
            $table->string('reference')->nullable()->unique()->after('account_details');

            // Stores the ID returned by InTouch after a successful transfer
            $table->string('gateway_transaction_id')->nullable()->after('reference');

            // Stores the currency, defaults to RWF
            $table->string('currency')->default('RWF')->after('amount');

            // Field to store API error messages if the disbursement fails
            $table->text('error_log')->nullable()->after('admin_note');

            // Tracks exactly when the money was sent
            $table->timestamp('processed_at')->nullable()->after('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
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