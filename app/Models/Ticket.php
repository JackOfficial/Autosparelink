<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
  protected $fillable = ['category', 'subject', 'message', 'order_ref', 'status', 'priority'];
}
