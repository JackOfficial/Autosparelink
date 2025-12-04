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
        Schema::create('teams', function (Blueprint $table) {
    $table->id();
    $table->foreignId('organization_id')->constrained()->cascadeOnDelete();
    
    $table->string('name');
    $table->string('position')->nullable();
    $table->string('email')->nullable()->unique();
    $table->string('phone')->nullable()->unique();
    $table->longText('bio')->nullable();
    
    // Social links — optional
    $table->string('facebook')->nullable()->unique();
    $table->string('linkedin')->nullable()->unique();
    $table->string('twitter')->nullable()->unique();
    
    $table->string('status')->default('active');
    $table->timestamps();
    $table->softDeletes();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teams');
    }
};
