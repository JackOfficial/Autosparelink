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
        Schema::table('order_items', function (Blueprint $table) {
            // We must include ALL old values + the NEW values
            $table->enum('status', [
                'pending', 
                'packed', 
                'ready_for_pickup', 
                'collected', 
                'at_hub', 
                'delivered', 
                'completed', 
                'cancelled'
            ])->default('pending')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            // Revert back to the original restricted list
            $table->enum('status', [
                'pending', 
                'packed', 
                'ready_for_pickup', 
                'collected', 
                'at_hub'
            ])->default('pending')->change();
        });
    }
};