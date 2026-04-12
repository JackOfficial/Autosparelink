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
        Schema::create('client_vehicles', function (Blueprint $table) {
            $table->id();
        
        // Ownership (Assuming you have a users table)
        $table->foreignId('user_id')->constrained()->onDelete('cascade');

        // Section 1: Basic Identity
        $table->foreignId('brand_id')->constrained()->onDelete('restrict');
        $table->foreignId('vehicle_model_id')->constrained()->onDelete('restrict');
        $table->integer('production_start'); // The "Year" field

        // Section 2: Specifications
        $table->string('trim_level')->nullable(); // Datalist input
        $table->foreignId('body_type_id')->constrained()->onDelete('restrict');
        $table->foreignId('engine_type_id')->constrained()->onDelete('restrict');
        $table->foreignId('transmission_type_id')->constrained()->onDelete('restrict');
        $table->string('displacement')->nullable(); // e.g., "2.0L" or "2000cc"
        $table->enum('steering_position', ['LHD', 'RHD'])->default('LHD');

        // Section 3: Extra Info
        $table->string('vin', 17)->nullable()->index(); // Indexed for faster lookups
        $table->boolean('is_primary')->default(false);

        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_vehicles');
    }
};
