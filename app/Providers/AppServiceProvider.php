<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\AliasLoader;

use Illuminate\Auth\Events\Login;
use App\Listeners\MigrateCartOnLogin;
use Illuminate\Support\Facades\Event;
use Illuminate\Pagination\Paginator; // Import this
use App\Observers\SpecificationObserver;
use App\Models\Specification;
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
       Event::listen(
        Login::class,
        MigrateCartOnLogin::class,
       );

       View::composer(['components.shop-dashboard', 'components.partials.*'], function ($view) {
        $view->with('shop', Auth::user()->shop ?? null);
       });

       Paginator::useBootstrapFive(); // Add this line
       Specification::observe(SpecificationObserver::class);

       view()->composer('admin.layouts.app', function ($view) {
    // Cache the counts for 1 minute to stay fast
    $counts = cache()->remember('admin_sidebar_counts', 60, function() {
        return [
            'abandoned' => DB::table('shoppingcart')->count(),
            'pending' => \App\Models\Order::where('status', 'pending')->count(),
        ];
    });

    $view->with('abandonedCount', $counts['abandoned']);
    $view->with('pendingOrdersCount', $counts['pending']);
});

    //    $loader = AliasLoader::getInstance();
    //   $loader->alias('Cart', \Darryldecode\Cart\Facades\CartFacade::class);
    }
}
