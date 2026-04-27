<?php

namespace App\Console\Commands;

use App\Models\Part;
use App\Models\User;
use App\Notifications\LowStockAlert;
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
    $threshold = 5;
    $lowStockParts = Part::where('stock_quantity', '<', $threshold)->get();

    if ($lowStockParts->isNotEmpty()) {
        // Use Spatie's role scope to find users with the 'admin' role
        $admins = User::role('admin')->get();
        
        if ($admins->isNotEmpty()) {
            foreach ($admins as $admin) {
                // Attach the collection to the admin object for the notification to use
                $admin->lowStockParts = $lowStockParts;
                $admin->notify(new LowStockAlert($admin));
            }
            $this->info('Low stock alerts sent to ' . $admins->count() . ' admin(s).');
        } else {
            $this->warn('No users found with the "admin" role.');
        }
    }
  }
}
