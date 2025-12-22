<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PartBrand extends Model
{
    protected $fillable = [
    'name',
    'country',
    'type',
    'description',
    'logo',
    'website',
];

}
