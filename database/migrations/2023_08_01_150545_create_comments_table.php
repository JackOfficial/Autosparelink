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
        Schema::create('comments', function (Blueprint $table) {
    $table->id(); // primary key
    $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // commenter
    $table->foreignId('blog_id')->constrained()->cascadeOnDelete(); // blog post
    $table->longText('comment'); // comment content
    $table->tinyInteger('status')->default(0); // 0 = pending, 1 = approved
    $table->timestamps();
    $table->softDeletes(); // allows recoverable deletion
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
