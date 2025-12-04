<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Partner extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'organization_id',
        'name',
        'website',
        'logo',
        'description',
    ];

    public function organization() {
        return $this->belongsTo(Organization::class);
    }
}
