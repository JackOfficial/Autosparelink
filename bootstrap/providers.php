<?php

return [
    App\Providers\AppServiceProvider::class,
    App\Providers\FortifyServiceProvider::class,
    App\Providers\RoleMiddlewareServiceProvider::class,
    // Add the Darryldecode provider here
    Darryldecode\Cart\CartServiceProvider::class,
];
