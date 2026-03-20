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
      Schema::table('comments', function (Blueprint $table) {
        // Remove the old specific columns
        $table->dropColumn(['blog_id']); 
        
        // Add polymorphic columns (commentable_id and commentable_type)
        $table->morphs('commentable'); 
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
