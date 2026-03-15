<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CheckLowStock extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-low-stock';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check low stock';

    /**
     * Execute the console command.
     */
    public function handle()
{
    // Define what "low stock" means for you (e.g., less than 5 items)
    $threshold = 5;
    $lowStockParts = \App\Models\Part::where('stock_quantity', '<', $threshold)->get();

    if ($lowStockParts->isNotEmpty()) {
        $admin = \App\Models\User::where('is_admin', true)->first();
        
        if ($admin) {
            // We pass the collection to a custom property or directly
            $admin->lowStockParts = $lowStockParts;
            $admin->notify(new \App\Notifications\LowStockAlert($admin));
            $this->info('Low stock alert sent to admin.');
        }
    }
}
}
