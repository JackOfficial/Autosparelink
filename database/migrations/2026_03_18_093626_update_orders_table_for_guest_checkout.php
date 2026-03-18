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
       Schema::table('orders', function (Blueprint $table) {
        // 1. Make user_id nullable so guests can skip login
        $table->foreignId('user_id')->nullable()->change();
        
        // 2. Make address_id nullable (guests won't have a saved address in the addresses table)
        $table->foreignId('address_id')->nullable()->change();

        // 3. Add guest-specific fields
        $table->string('guest_name')->nullable()->after('address_id');
        $table->string('guest_email')->nullable()->after('guest_name');
        $table->string('guest_phone')->nullable()->after('guest_email');
        $table->text('guest_shipping_address')->nullable()->after('guest_phone');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
