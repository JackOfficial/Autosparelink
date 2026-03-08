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
        Schema::create('blog_categories', function (Blueprint $table) {
        $table->id();                       // primary key
        $table->string('name');             // category name
        $table->string('slug')->unique();   // SEO-friendly URL
        $table->string('photo')->nullable(); // optional image
        $table->timestamps();               // created_at & updated_at
        $table->softDeletes();              // allows recoverable deletion
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_categories');
    }
};
