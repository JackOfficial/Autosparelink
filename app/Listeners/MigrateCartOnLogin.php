<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Auth\Events\Login;
use Cart;

class MigrateCartOnLogin
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
       $user = $event->user;

        // 1. Restore the user's saved cart from the database
        // We use the User ID as the identifier
        Cart::instance('default')->restore($user->id);

        // 2. The 'restore' method automatically merges the 
        // current session items with the database items.

        // 3. Immediately store it back to ensure the 
        // session items are now persisted in the DB.
        Cart::instance('default')->store($user->id);
    }
}
