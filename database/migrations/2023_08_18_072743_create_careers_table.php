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
       Schema::create('careers', function (Blueprint $table) {
    $table->id();
    $table->foreignId('job_type_id')->constrained('job_types')->cascadeOnDelete();
    $table->string('title');
    $table->longText('description')->nullable();
    $table->string('qualification')->nullable();
    $table->date('deadline')->nullable();
    $table->enum('status', ['open', 'closed', 'paused'])->default('open');
    $table->timestamps();
    $table->softDeletes();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('careers');
    }
};
