<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Broadcast extends Model
{
    protected $fillable = [
    'type', 
    'message', 
    'url', 
    'recipient_count'
];
}
