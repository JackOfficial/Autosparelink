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

            // Dispatched to Alpine.js listener for a centered, large modal
            $this->dispatch('swal', [
                'icon'  => 'success',
                'title' => 'Awesome!',
                'text'  => 'You have subscribed successfully to our newsletter.',
                // Note: Removing 'toast' and 'position' makes it center/large
            ]);
        } else {
            $this->dispatch('swal', [
                'icon'  => 'error',
                'title' => 'Subscription Failed',
                'text'  => 'We could not process your subscription at this time.',
            ]);
        }
    }

    public function render()
    {
        return view('livewire.subscribe-component');
    }
}