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
        Schema::dropIfExists('destination_variant');
      Schema::create('destination_specification', function (Blueprint $table) {
        $table->id();
        $table->foreignId('specification_id')->constrained()->onDelete('cascade');
        $table->foreignId('destination_id')->constrained()->onDelete('cascade');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('specifications', function (Blueprint $table) {
            //
        });
    }
};
