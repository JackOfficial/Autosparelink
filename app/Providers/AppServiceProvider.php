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
use App\Observers\OrderItemObserver;
use App\Observers\ShopObserver;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
   public function boot(): void
{
    Schema::defaultStringLength(191);

    // Registering observers
    Shop::observe(ShopObserver::class);
    OrderItem::observe(OrderItemObserver::class);
    Specification::observe(SpecificationObserver::class);

    // UI Configuration
    Paginator::useBootstrapFive();

    // Event Listeners
    Event::listen(
        Login::class,
        MigrateCartOnLogin::class,
    );

    /**
     * Sidebar/Layout Stats for the User & Seller Dashboard
     * Handles both customer metrics and vendor wallet data.
     */
    View::composer(['layouts.dashboard', 'user.*'], function ($view) {
        if (Auth::check()) {
            $user = Auth::user();

            // 1. Fetch Ticket Stats
            $ticketStats = $user->tickets()
                ->selectRaw("status, count(*) as total")
                ->groupBy('status')
                ->pluck('total', 'status');

            // 2. Prepare Base Customer Stats
            $stats = [
                'total_orders'    => $user->orders()->count(),
                'active_orders'   => $user->orders()->whereIn('status', ['pending', 'processing', 'shipped'])->count(),
                'total_spent'     => (float) $user->orders()->where('status', 'completed')->sum('total_amount'),
                'pending_tickets' => $ticketStats['pending'] ?? 0,
                'open_tickets'    => $ticketStats['open'] ?? 0,
                'closed_tickets'  => $ticketStats['closed'] ?? 0,
            ];

            // 3. Prepare Seller Wallet Stats (If applicable)
            if ($user->hasRole('seller') && $user->shop) {
                $wallet = $user->shop->wallet;
                $view->with('seller_stats', [
                    'balance'      => $wallet->balance,
                    'total_earned' => $wallet->total_earnings, // Uses your Wallet model accessor
                    'pending'      => $wallet->pending_balance,
                    'currency'     => $wallet->currency ?? 'RWF',
                ]);
            }

            $view->with('stats', $stats);
        }
    });

    /**
     * Shop Context for specific dashboard components
     */
    View::composer(['components.shop-dashboard', 'components.partials.*'], function ($view) {
        if (Auth::check()) {
            $view->with('shop', Auth::user()->shop ?? null);
        }
    });

    /**
     * Admin Layout Counts (with 1-minute caching for performance)
     */
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