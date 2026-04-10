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
        Schema::create('payouts', function (Blueprint $table) {
           $table->id();
        $table->foreignId('shop_id')->constrained()->onDelete('cascade');
        $table->decimal('amount', 15, 2);
        $table->string('payout_method'); // e.g., MTN MoMo, Airtel, Bank
        $table->string('account_details'); // Phone or Acc number
        $table->enum('status', ['pending', 'processing', 'completed', 'rejected'])->default('pending');
        $table->text('admin_note')->nullable(); // For rejection reasons or TX IDs
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payouts');
    }
};
