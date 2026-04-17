<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('commissions', function (Blueprint $table) {
            // Remove the link to specific shops and the type categorization
            $table->dropForeign(['shop_id']); 
            $table->dropColumn(['shop_id', 'type']);
        });
    }

    public function down(): void
    {
        Schema::table('commissions', function (Blueprint $table) {
            $table->string('type')->default('global');
            $table->foreignId('shop_id')->nullable()->constrained();
        });
    }
};