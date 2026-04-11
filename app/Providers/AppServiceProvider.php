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
        
        Paginator::useBootstrapFive();
        
        Event::listen(
            Login::class,
            MigrateCartOnLogin::class,
        );

        // Sidebar/Layout Stats for the User Dashboard
        View::composer(['layouts.dashboard', 'user.*'], function ($view) {
    if (Auth::check()) {
        $user = Auth::user();
        
        $ticketStats = $user->tickets()
            ->selectRaw("status, count(*) as total")
            ->groupBy('status')
            ->pluck('total', 'status');

        $view->with('stats', [
            'total_orders'   => $user->orders()->count(),
            'active_orders'  => $user->orders()->whereIn('status', ['pending', 'processing', 'shipped'])->count(),
            'total_spent'    => (float) $user->orders()->where('status', 'completed')->sum('total_amount'),
            'pending_tickets'=> $ticketStats['pending'] ?? 0,
            'open_tickets'   => $ticketStats['open'] ?? 0,
            'closed_tickets' => $ticketStats['closed'] ?? 0,
        ]);
    }
});

        // Shop Context for components
        View::composer(['components.shop-dashboard', 'components.partials.*'], function ($view) {
            $view->with('shop', Auth::user()->shop ?? null);
        });

        // Specification Observer
        Specification::observe(SpecificationObserver::class);

        // Admin Layout Counts (with simple caching)
        view()->composer('admin.layouts.app', function ($view) {
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