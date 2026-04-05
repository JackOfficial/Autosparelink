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
        Schema::create('shops', function (Blueprint $table) {
           $table->id();
           $table->foreignId('user_id')->constrained()->onDelete('cascade'); // The owner (User)
           $table->string('name');
           $table->string('slug')->unique(); // for autosparelink.rw/shop/kigali-parts
           $table->text('description')->nullable();
           $table->string('logo')->nullable();
           $table->string('tin_number')->nullable(); // Important for Rwandan business verification
           $table->string('address')->nullable(); // Physical location (e.g., Nyabugogo)
           $table->string('phone_number')->nullable();
           $table->boolean('is_active')->default(false); // Admin must approve the shop first
           $table->decimal('commission_rate', 5, 2)->default(10.00); // Specific rate for this seller
           $table->boolean('is_verified')->default(false);
           $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shops');
    }
};
