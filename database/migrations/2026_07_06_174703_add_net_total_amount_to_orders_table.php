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
        Schema::table('orders', function (Blueprint $blueprint) {
            // Adds the net total column after the total_amount column
            $blueprint->integer('net_total_amount')
                ->default(0)
                ->after('total_amount')
                ->comment('Sum of all vendor payouts for this order excluding admin markup');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $blueprint) {
            $blueprint->dropColumn('net_total_amount');
        });
    }
};