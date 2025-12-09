<?php

namespace App\Livewire;

use App\Models\Subscription;
use Livewire\Component;

class SubscribeComponent extends Component
{
    public $email = '';

    public function subscribe(){
        $this->validate([
         'email' => 'required|email|unique:subscriptions,email'
        ]);

        $subscribe = Subscription::create([
            'email' => $this->email
        ]);

        if($subscribe){
            $this->reset('email');
            session()->flash('subscribeSuccess', 'You have subscribed successfully');
        }
        else{
            $this->flush('subscribeFail', 'You could not be subscribed');
        }
    }

    public function render()
    {
        return view('livewire.subscribe-component');
    }
}
