<?
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('specifications', function (Blueprint $table) {

            if (!Schema::hasColumn('specifications', 'vehicle_model_id')) {
                $table->foreignId('vehicle_model_id')->nullable()->after('variant_id')->constrained()->cascadeOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('specifications', function (Blueprint $table) {
            if (Schema::hasColumn('specifications', 'vehicle_model_id')) {
                $table->dropForeign(['vehicle_model_id']);
            }
        });
    }
};
