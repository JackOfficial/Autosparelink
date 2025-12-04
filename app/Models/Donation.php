<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Donation extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'card_name',
        'card_number',
        'cvv',
        'expiry_date',
        'amount',
    ];

    public function donor() {
        return $this->belongsTo(Donor::class);
    }

    public function project() {
        return $this->belongsTo(Project::class);
    }
}
