<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\PaymentLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class CleanupPendingOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'asl:cleanup-orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove pending orders and logs older than 24 hours';

    /**
     * Execute the console command.
     */
   public function handle()
    {
        $cutoff = Carbon::now()->subHours(24);

        $this->info("Cleaning up data older than: " . $cutoff);

        DB::beginTransaction();

        try {
            // 1. Delete old pending Payment Logs
            $logsDeleted = PaymentLog::where('status', 'pending')
                ->where('created_at', '<', $cutoff)
                ->delete();

            // 2. Delete old pending Orders 
            // Note: This will also delete OrderItems if you have 'onDelete cascade' in migrations
            $ordersDeleted = Order::where('status', 'pending')
                ->where('created_at', '<', $cutoff)
                ->delete();

            DB::commit();

            $this->info("Successfully deleted {$logsDeleted} logs and {$ordersDeleted} orders.");
            
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("Cleanup failed: " . $e->getMessage());
        }
    }
}
