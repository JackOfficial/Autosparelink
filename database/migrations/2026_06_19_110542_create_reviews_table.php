<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Creates reviewable_id and reviewable_type strings
            $table->morphs('reviewable'); 
            
            $table->unsignedTinyInteger('rating'); // 1-5 rating system
            $table->text('comment')->nullable();
            
            // Standard moderation system for auto parts listings
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();

            // Prevents a single user from double-rating the same item or same shop
            $table->unique(['user_id', 'reviewable_id', 'reviewable_type'], 'user_reviewable_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
