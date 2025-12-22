<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Part extends Model
{
    protected $fillable = [
        'part_number',
        'part_name',
        'category_id',
        'brand_id',
        'description',
        'price',
        'stock_quantity',
        'status',
        'photo',
    ];


    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function brand()
    {
        return $this->belongsTo(PartBrand::class);
    }
}
