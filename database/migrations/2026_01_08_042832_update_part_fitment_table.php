<? 

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('part_fitments', function (Blueprint $table) {
            // Add new columns
            // if (!Schema::hasColumn('part_fitments', 'start_year')) {
            //     $table->year('start_year')->nullable()->after('variant_id');
            // }

            // if (!Schema::hasColumn('part_fitments', 'end_year')) {
            //     $table->year('end_year')->nullable()->after('start_year');
            // }

            // // Update status column if it exists as integer and you want enum
            // if (Schema::hasColumn('part_fitments', 'status')) {
            //     $table->enum('status', ['active', 'inactive'])->default('active')->change();
            // }

            // // Add unique constraint if it doesn't exist
            // $sm = Schema::getConnection()->getDoctrineSchemaManager();
            // $indexes = $sm->listTableIndexes('part_fitments');
            // if (!array_key_exists('part_fitments_part_id_variant_id_unique', $indexes)) {
            //     $table->unique(['part_id', 'variant_id']);
            // }
        });
    }

    public function down(): void
    {
        Schema::table('part_fitments', function (Blueprint $table) {
            if (Schema::hasColumn('part_fitments', 'start_year')) {
                $table->dropColumn('start_year');
            }
            if (Schema::hasColumn('part_fitments', 'end_year')) {
                $table->dropColumn('end_year');
            }

            // Optional: revert status column to integer
            if (Schema::hasColumn('part_fitments', 'status')) {
                $table->integer('status')->default(1)->change();
            }

            // Optional: drop unique index
            $table->dropUnique(['part_id', 'variant_id']);
        });
    }
};
