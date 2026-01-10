<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class ModelForm extends Component
{
      // ================= MODEL FIELDS =================
    public $name = "Jack";

    public function save(){
        dd($this->name);
    }

    public function render()
    {
        return view('livewire.admin.model-form');
    }
}
