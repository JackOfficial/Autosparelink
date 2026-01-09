<?

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('specifications', function (Blueprint $table) {
            $table->dropForeign(['variant_id']);
    $table->unsignedBigInteger('variant_id')->nullable()->change();
    $table->foreign('variant_id')->references('id')->on('variants')->cascadeOnDelete();

    if (!Schema::hasColumn('specifications', 'vehicle_model_id')) {
        $table->foreignId('vehicle_model_id')->nullable()->after('variant_id')->constrained()->cascadeOnDelete();
    }
        });
    }

    public function down(): void
    {
        Schema::table('specifications', function (Blueprint $table) {
            $table->dropForeign(['variant_id']);
            if (Schema::hasColumn('specifications', 'vehicle_model_id')) {
                $table->dropForeign(['vehicle_model_id']);
            }
            $table->foreignId('variant_id')->nullable(false)->change();
            $table->foreign('variant_id')->references('id')->on('variants')->cascadeOnDelete();
            if (Schema::hasColumn('specifications', 'vehicle_model_id')) {
                $table->dropColumn('vehicle_model_id');
            }
        });
    }
};
