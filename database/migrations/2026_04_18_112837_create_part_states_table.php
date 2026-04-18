<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB; // Corrected Import

return new class extends Migration
{
    public function up(): void
    {
        // 1. Create the lookup table
        Schema::create('part_states', function (Blueprint $table) {
            $table->id();
            $table->string('name'); 
            $table->string('slug')->unique();
            $table->timestamps();
        });

        // 2. Add foreign key to your existing parts table
        Schema::table('parts', function (Blueprint $table) {
            $table->foreignId('part_state_id')->after('id')->constrained()->onDelete('cascade');
        });

        // 3. Optional: Seed the data immediately so your app doesn't break
        DB::table('part_states')->insert([
            ['name' => 'New', 'slug' => 'new'],
            ['name' => 'Used', 'slug' => 'used'],
            ['name' => 'Refurbished', 'slug' => 'refurbished'],
        ]);
    }

    public function down(): void
    {
        // Must drop the foreign key column BEFORE dropping the table
        Schema::table('parts', function (Blueprint $table) {
            $table->dropForeign(['part_state_id']);
            $table->dropColumn('part_state_id');
        });

        Schema::dropIfExists('part_states');
    }
};