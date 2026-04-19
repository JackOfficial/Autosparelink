<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Auth\Events\Login;
use App\Listeners\MigrateCartOnLogin;
use Illuminate\Support\Facades\Event;
use Illuminate\Pagination\Paginator;
use App\Observers\SpecificationObserver;
use App\Models\Specification;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Shop;
use App\Models\Payout; // Import Payout
use App\Models\Commission; // Import Commission
use App\Observers\OrderItemObserver;
use App\Observers\ShopObserver;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Schema::defaultStringLength(191);

        Shop::observe(ShopObserver::class);
        OrderItem::observe(OrderItemObserver::class);
        Specification::observe(SpecificationObserver::class);

        Paginator::useBootstrapFive();

        Event::listen(
            Login::class,
            MigrateCartOnLogin::class,
        );

        /**
         * Sidebar/Layout Stats for the User & Seller Dashboard
         */
        View::composer(['layouts.dashboard', 'admin.layouts.app', 'components.shop-dashboard'], function ($view) {
            if (Auth::check()) {
                $user = Auth::user();

                // 1. Fetch Ticket Stats
                $ticketStats = $user->tickets()
                    ->selectRaw("status, count(*) as total")
                    ->groupBy('status')
                    ->pluck('total', 'status');

                $stats = [
                    'total_orders'    => $user->orders()->count(),
                    'active_orders'   => $user->orders()->whereIn('status', ['pending', 'processing', 'shipped'])->count(),
                    'total_spent'     => (float) $user->orders()->where('status', 'completed')->sum('total_amount'),
                    'pending_tickets' => $ticketStats['pending'] ?? 0,
                    'open_tickets'    => $ticketStats['open'] ?? 0,
                    'closed_tickets'  => $ticketStats['closed'] ?? 0,
                ];

                // 2. Prepare Seller Wallet Stats - AUDITED VERSION
                if ($user->hasRole('seller') && $user->shop) {
                    $shopId = $user->shop->id;
                    $rate = Commission::getRate() / 100;

                    // Calculate Earnings from Completed Items
                    $totalGross = OrderItem::where('shop_id', $shopId)
                        ->where('status', 'completed')
                        ->whereHas('order', fn($q) => $q->where('status', 'completed'))
                        ->sum(DB::raw('unit_price * quantity'));

                    $netEarnings = $totalGross * (1 - $rate);

                    // Calculate All Deductions (Sent + Pending + Processing)
                    $totalDeductions = Payout::where('shop_id', $shopId)
                        ->whereIn('status', ['completed', 'pending', 'processing'])
                        ->sum('amount');

                    // This is the "Audited" balance that updates instantly
                    $auditedBalance = $netEarnings - $totalDeductions;

                    $view->with('seller_stats', [
                        'balance'      => $auditedBalance,
                        'total_earned' => $netEarnings, 
                        'pending'      => Payout::where('shop_id', $shopId)->whereIn('status', ['pending', 'processing'])->sum('amount'),
                        'currency'     => 'RWF',
                    ]);
                }

                $view->with('stats', $stats);
            }
        });

        // Rest of your composers...
        View::composer(['components.shop-dashboard', 'components.partials.*'], function ($view) {
            if (Auth::check()) {
                $view->with('shop', Auth::user()->shop ?? null);
            }
        });

        View::composer('admin.layouts.app', function ($view) {
            $counts = cache()->remember('admin_sidebar_counts', 60, function() {
                return [
                    'abandoned' => DB::table('shoppingcart')->count(),
                    'pending'   => Order::where('status', 'pending')->count(),
                ];
            });
            $view->with('abandonedCount', $counts['abandoned']);
            $view->with('pendingOrdersCount', $counts['pending']);
        });
    }
}