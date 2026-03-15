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
        Schema::create('payment_logs', function (Blueprint $table) {
           $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('tx_ref')->unique(); // Our ASL-XXXX reference
            $table->string('transaction_id')->nullable(); // Flutterwave's ID
            $table->decimal('amount', 15, 2);
            $table->string('currency', 3)->default('RWF');
            $table->string('status')->default('pending'); // pending, successful, failed
            $table->text('error_message')->nullable();
            $table->json('raw_response')->nullable(); // Stores the full API data for debugging
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_logs');
    }
};
