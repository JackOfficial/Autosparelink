<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Handle the rename only if 'name' still exists
        if (Schema::hasColumn('shops', 'name')) {
            Schema::table('shops', function (Blueprint $table) {
                $table->renameColumn('name', 'shop_name');
            });
        }

        // 2. Handle the email addition only if it doesn't exist yet
        if (!Schema::hasColumn('shops', 'shop_email')) {
            Schema::table('shops', function (Blueprint $table) {
                // We use 'after' conditionally or just add it
                $table->string('shop_email')->nullable()->after('shop_name');
            });
        }
    }

    public function down(): void
    {
        Schema::table('shops', function (Blueprint $table) {
            if (Schema::hasColumn('shops', 'shop_name')) {
                $table->renameColumn('shop_name', 'name');
            }
            $table->dropColumn('shop_email');
        });
    }
};