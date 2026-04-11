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
        Schema::table('addresses', function (Blueprint $col) {
            // Adding Rwandan specific location hierarchy
            $col->string('province')->nullable()->after('full_name');
            $col->string('district')->nullable()->after('province');
            $col->string('sector')->nullable()->after('district');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('addresses', function (Blueprint $col) {
            $col->dropColumn(['province', 'district', 'sector']);
        });
    }
};
