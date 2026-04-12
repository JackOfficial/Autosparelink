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
        Schema::table('shops', function (Blueprint $table) {
            // 1. Rename 'name' to 'shop_name'
            $table->renameColumn('name', 'shop_name');

            // 2. Add 'shop_email' after the renamed shop_name
            $table->string('shop_email')->after('shop_name')->nullable(); 
            
            // Note: If 'name' is already renamed, use 'shop_name' in the 'after' method
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->renameColumn('shop_name', 'name');
            $table->dropColumn('shop_email');
        });
    }
};