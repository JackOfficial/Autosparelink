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
        Schema::create('contacts', function (Blueprint $table) {
    $table->id();
    $table->string('name')->nullable();         // optional name
    $table->string('email')->unique();          // unique email
    $table->string('phone')->nullable();        // optional phone
    $table->text('message');                    // contact message
    $table->enum('status', ['active', 'resolved', 'archived'])->default('active'); // clear statuses
    $table->timestamps();
    $table->softDeletes();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
