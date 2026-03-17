<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Disable strict mode for this session
        DB::statement("SET SESSION sql_mode = ''");

        // 2. Perform the update
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'callback_requested', 'paid', 'processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending'");
        
        // 3. (Optional) If row 4 has weird data, we fix it
        DB::table('orders')->whereNotIn('status', ['pending', 'callback_requested', 'paid', 'processing', 'shipped', 'delivered', 'cancelled'])
            ->update(['status' => 'pending']);
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'processing', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending'");
    }
};