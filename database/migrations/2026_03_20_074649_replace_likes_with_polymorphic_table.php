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
       // 1. Remove the old table if it exists
        Schema::dropIfExists('likes');

        // 2. Create the new Polymorphic table
        Schema::create('likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            /** * morphs('likeable') creates:
             * - likeable_id (BigInt): The ID of the Blog, News, or Comment
             * - likeable_type (String): The Model class name (e.g., 'App\Models\Blog')
             */
            $table->morphs('likeable'); 

            $table->boolean('is_like')->default(true); // true = Like, false = Dislike
            $table->timestamps();

            // 3. Prevent a user from liking/disliking the same item more than once
            $table->unique(['user_id', 'likeable_id', 'likeable_type'], 'user_like_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       Schema::dropIfExists('likes');
        
        // Optional: Recreate the old table structure if you ever need to rollback
        Schema::create('likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('blog_id')->constrained()->cascadeOnDelete();
            $table->boolean('like')->default(true);
            $table->tinyInteger('status')->default(0);
            $table->timestamps();
        });
    }
};
