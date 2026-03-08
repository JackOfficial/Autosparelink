<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class TestComponent extends Component
{
    public $name = "Jack";

    public function save(){
        dd($this->name);
    }
    
    public function render()
    {
        return view('livewire.admin.test-component');
    }
}
