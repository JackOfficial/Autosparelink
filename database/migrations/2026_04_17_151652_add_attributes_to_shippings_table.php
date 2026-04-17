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
        Schema::table('shippings', function (Blueprint $table) {
            // Link to Address model (Nullable for Guest Checkouts)
            $table->foreignId('address_id')
                ->nullable()
                ->after('shop_id')
                ->constrained()
                ->onDelete('set null');

            // Fallback for Guest Checkout or Snapshotted Address
            $table->text('address_text')
                ->nullable()
                ->after('address_id')
                ->comment('Full address string for guest checkouts');

            // Direct contact info (Denormalized for courier speed)
            $table->string('recipient_name')->nullable()->after('address_text');
            $table->string('recipient_phone')->nullable()->after('recipient_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shippings', function (Blueprint $table) {
            $table->dropForeign(['address_id']);
            $table->dropColumn([
                'address_id',
                'address_text',
                'recipient_name',
                'recipient_phone'
            ]);
        });
    }
};