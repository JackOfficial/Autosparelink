<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $fillable = [
        'brand_name',
        'slug',
        'brand_logo',
        'description',
        'country',
        'website',
    ];

     public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function vehicleModels()
    {
        return $this->hasMany(VehicleModel::class, 'brand_id');
    }

      public function parts()
    {
        return $this->hasMany(Part::class, 'part_brand_id'); 
        // 'part_brand_id' is the foreign key in parts table
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
