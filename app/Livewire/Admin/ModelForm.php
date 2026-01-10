<?php

namespace App\Livewire\Admin;

use App\Models\Brand;
use App\Models\VehicleModel;
use App\Models\Specification;
use App\Models\BodyType;
use App\Models\EngineType;
use App\Models\TransmissionType;
use App\Models\DriveType;
use Illuminate\Support\Facades\DB;
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
