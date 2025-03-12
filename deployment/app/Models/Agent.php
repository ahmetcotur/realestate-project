<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Agent extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'title',
        'email',
        'phone',
        'bio',
        'photo',
        'slug',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function properties(): HasMany
    {
        return $this->hasMany(Property::class);
    }

    /**
     * Emlak danışmanı ile ilişkili kullanıcı
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }
}
