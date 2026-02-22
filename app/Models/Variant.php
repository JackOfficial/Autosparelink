<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Str;

class Variant extends Model
{
    protected $fillable = [
        'vehicle_model_id',
        'name',
        'slug',
        'production_year',
        'chassis_code',
        'model_code',
        'trim_level',
        'is_default',
        'status',
    ];

    /**
     * Boot the model.
     */
    protected static function booted()
    {
        static::saving(function ($variant) {
            // Automatically calculate name/slug before any database write.
            // This ensures changes to trim_level or production_year are caught.
            $variant->generateNameSilently();
        });
    }

    /**
     * Internal logic to build the name and slug without triggering a new save.
     */
    public function generateNameSilently()
    {
        // Load relationships if they aren't already present to ensure name accuracy
        $this->loadMissing([
            'vehicleModel.brand', 
            'specifications.bodyType', 
            'specifications.engineType', 
            'specifications.transmissionType', 
            'specifications.engineDisplacement'
        ]);

        $spec = $this->specifications->first();
        if (!$spec) return;

        $model = $this->vehicleModel;

        $pieces = [
            $model?->brand?->brand_name,
            $model?->model_name,
            $this->trim_level,
            $spec->bodyType?->name,
            $this->production_year,
            $spec->engineDisplacement?->name,
            $spec->engineType?->name,
            $spec->transmissionType?->name,
        ];

        $this->name = implode(' ', array_filter($pieces));
        $this->slug = Str::slug($this->name . '-' . ($this->chassis_code ?? Str::random(4)));
    }

    /**
     * Compatibility Method: Syncs name from specification and saves to DB.
     * Keep this so existing code calling this method does not break.
     */
    public function syncNameFromSpec()
    {
        // Simply calling save() triggers the 'saving' hook in booted()
        return $this->save();
    }

    /* =======================
     | Relationships
     ======================= */

    public function vehicleModel(): BelongsTo
    {
        return $this->belongsTo(VehicleModel::class);
    }

    public function specifications(): HasMany
    {
        return $this->hasMany(Specification::class);
    }

    public function activeSpecifications(): HasMany
    {
        return $this->hasMany(Specification::class)->where('status', 1);
    }

    public function destinations(): BelongsToMany
    {
        return $this->belongsToMany(Destination::class);
    }

    public function parts(): BelongsToMany
    {
        return $this->belongsToMany(Part::class, 'part_fitments', 'variant_id', 'part_id')
            ->withPivot(['vehicle_model_id', 'status', 'year_start', 'year_end'])
            ->withTimestamps();
    }

    public function fitments(): HasMany
    {
        return $this->hasMany(PartFitment::class);
    }

    public function photos(): MorphMany
    {
        return $this->morphMany(Photo::class, 'imageable');
    }

    /* =======================
     | Accessors
     ======================= */

    public function getFullNameAttribute(): string
    {
        return $this->name;
    }
}