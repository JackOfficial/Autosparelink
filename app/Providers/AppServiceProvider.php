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
use App\Models\Payout;
use App\Models\WalletTransaction;
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
         * Sidebar/Layout Stats for User & Seller Dashboard
         */
        View::composer(['layouts.*', 'user.*', 'admin.layouts.app', 'components.shop-dashboard', 'components.partials.*'], function ($view) {
            // Set defaults to completely prevent "Undefined variable" view bugs for guests
            $stats = [
                'total_orders'    => 0,
                'active_orders'   => 0,
                'total_spent'     => 0.00,
                'pending_tickets' => 0,
                'open_tickets'    => 0,
                'closed_tickets'  => 0,
            ];

            if (Auth::check()) {
                $user = Auth::user();

                // Cache statistics per user request lifecycle to completely fix N+1 duplicate queries
                $stats = cache()->driver('array')->remember("user_stats_{$user->id}", 1, function () use ($user) {
                    $ticketStats = $user->tickets()
                        ->selectRaw("status, count(*) as total")
                        ->groupBy('status')
                        ->pluck('total', 'status');

                    return [
                        'total_orders'    => $user->orders()->count(),
                        'active_orders'   => $user->orders()->whereIn('status', ['pending', 'processing', 'shipped'])->count(),
                        'total_spent'     => (float) $user->orders()->where('status', 'completed')->sum('total_amount'),
                        'pending_tickets' => $ticketStats['pending'] ?? 0,
                        'open_tickets'    => $ticketStats['open'] ?? 0,
                        'closed_tickets'  => $ticketStats['closed'] ?? 0,
                    ];
                });

                // Prepare Seller Wallet Stats directly from your unified Wallet table
                if ($user->hasRole('seller') && $user->shop) {
                    $shop = $user->shop;
                    $wallet = $shop->wallet;

                    // Pull directly from the wallet record instead of repeating heavy aggregate loops
                    $view->with('seller_stats', [
                        'balance'      => (float) ($wallet->balance ?? 0), 
                        'total_earned' => (float) WalletTransaction::whereHas('wallet', fn($q) => $q->where('shop_id', $shop->id))
                                            ->where('type', 'credit')
                                            ->where('status', 'completed')
                                            ->sum('amount'), 
                        'pending'      => (float) ($wallet->pending_balance ?? Payout::where('shop_id', $shop->id)->whereIn('status', ['pending', 'processing'])->sum('amount') ?? 0),
                        'currency'     => 'RWF',
                    ]);

                    // Share shop across matching dashboard sub-views safely
                    $view->with('shop', $shop);
                }
            }

            $view->with('stats', $stats);
        });

        // Admin Layout Counts
        View::composer('admin.layouts.app', function ($view) {
            $counts = cache()->remember('admin_sidebar_counts', 60, function() {
                return [
                    'abandoned' => DB::table('shoppingcart')->count(),
                    'pending'   => Order::where('status', 'pending')->count(),
                    'payouts'   => Payout::where('status', 'pending')->count(),
                ];
            });

            $view->with([
                'abandonedCount'      => $counts['abandoned'],
                'pendingOrdersCount'  => $counts['pending'],
                'pendingPayoutsCount' => $counts['payouts'],
            ]);
        });
    }
}