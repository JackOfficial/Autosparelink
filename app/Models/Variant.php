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
        'trim_level',
        'is_default',
        'status',
        // chassis_code and model_code removed as they are now in specifications
    ];

    /**
     * Boot the model.
     */
    protected static function booted()
    {
        static::saving(function ($variant) {
            // Automatically calculate name and slug before any database write.
            $variant->generateNameSilently();
        });
    }

    /**
     * Internal logic to build the name and slug without triggering a new save.
     */
    public function generateNameSilently()
    {
        // 1. Load missing relationships, now including chassis_code from spec
        $this->loadMissing([
            'vehicleModel.brand', 
            'specifications.bodyType', 
            'specifications.engineType', 
            'specifications.transmissionType', 
            'specifications.engineDisplacement'
        ]);

        $spec = $this->specifications->first();
        $model = $this->vehicleModel;

        // 2. Build the name pieces
        $pieces = [
            $model?->brand?->brand_name,
            $model?->model_name,
            $this->trim_level,
            $spec?->bodyType?->name,
            $this->production_year,
            $spec?->engineDisplacement?->name,
            $spec?->engineType?->name,
            $spec?->transmissionType?->name,
        ];  

        // Generate the Name
        $newName = implode(' ', array_filter($pieces));
        $this->name = $newName ?: 'Unnamed Variant';

        // 3. Generate Unique Slug
        // We now pull chassis_code from the SPECIFICATION relationship
        $chassis = $spec?->chassis_code ?? '';
        $slugBase = Str::slug($this->name . '-' . $chassis);
        
        // Fallback if slug is empty
        if (empty($slugBase)) {
            $slugBase = Str::slug($model?->model_name ?? 'variant') . '-' . Str::random(5);
        }

        $this->slug = $this->makeSlugUnique($slugBase);
    }

    /**
     * Ensures the slug is unique in the database
     */
    protected function makeSlugUnique($slug)
    {
        $originalSlug = $slug;
        $count = 1;

        while (static::where('slug', $slug)
                     ->where('id', '!=', $this->id)
                     ->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        return $slug;
    }

    /**
     * Compatibility Method: Syncs name from specification and saves to DB.
     */
    public function syncNameFromSpec()
    {
        // Triggers the 'saving' hook which calls generateNameSilently()
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