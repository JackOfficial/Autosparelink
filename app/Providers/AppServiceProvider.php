<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\AliasLoader;

use Illuminate\Auth\Events\Login;
use App\Listeners\MigrateCartOnLogin;
use Illuminate\Support\Facades\Event;

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
    //    $loader = AliasLoader::getInstance();
    //   $loader->alias('Cart', \Darryldecode\Cart\Facades\CartFacade::class);
    }
}
