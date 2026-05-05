<?php

namespace App\Livewire;

use App\Models\Subscription;
use Livewire\Component;

class SubscribeComponent extends Component
{
    public $email = '';

    public function subscribe()
    {
        $this->validate([
            'email' => 'required|email|unique:subscriptions,email'
        ]);

        $subscribe = Subscription::create([
            'email' => $this->email
        ]);

        if ($subscribe) {
            $this->reset('email');

            // Dispatching for a prominent, centered modal
            $this->dispatch('swal', [
                'icon'        => 'success',
                'title'       => 'Welcome to the Family!',
                'text'        => 'Your subscription was successful. Stay tuned for updates!',
                'isPremium'   => true, // Custom flag for our Alpine listener
            ]);
        } else {
            $this->dispatch('swal', [
                'icon'  => 'error',
                'title' => 'System Error',
                'text'  => 'We couldn’t process that. Please try again later.',
            ]);
        }
    }

    public function render()
    {
        return view('livewire.subscribe-component');
    }
}