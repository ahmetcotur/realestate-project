<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        'title_tr',
        'title_en',
        'description_tr',
        'description_en',
        'location',
        'price',
        'currency',
        'bedrooms',
        'bathrooms',
        'area',
        'latitude',
        'longitude',
        'property_type_id',
        'agent_id',
        'features',
        // 'images', // Bu alanı kaldırıyoruz çünkü images() metodu ile çakışıyor
        'slug',
        'is_featured',
        'status',
        'is_active',
    ];

    protected $casts = [
        'features' => 'array',
        // 'images' => 'array', // Bu alanı kaldırıyoruz çünkü images() metodu ile çakışıyor
        'is_featured' => 'boolean',
        'price' => 'decimal:2',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'is_active' => 'boolean',
    ];

    /**
     * The "booted" method of the model.
     * Danışmanlar sadece kendi emlak ilanlarını görecek şekilde global scope ekler
     */
    protected static function booted()
    {
        static::addGlobalScope('agentProperties', function (Builder $builder) {
            if (Auth::check() && Auth::user()->isAgent()) {
                $builder->where('agent_id', Auth::user()->agent_id);
            }
        });
    }

    public function propertyType(): BelongsTo
    {
        return $this->belongsTo(PropertyType::class);
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(Agent::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(PropertyImage::class);
    }

    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class);
    }
}
