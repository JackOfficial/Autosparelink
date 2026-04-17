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
            // Adding 'disputed' and 'returned' to the allowed statuses
            $table->enum('status', [
                'pending', 
                'packed', 
                'ready_for_pickup', 
                'collected', 
                'at_hub', 
                'delivered', 
                'completed', 
                'cancelled',
                'disputed', // For when the client opens a complaint
                'returned'  // For when the item is physically sent back
            ])->default('pending')->change();
            
            // It is also a good time to ensure you have a notes column 
            // to store WHY the user disputed the item.
            if (!Schema::hasColumn('order_items', 'notes')) {
                $table->text('notes')->nullable()->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
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
};