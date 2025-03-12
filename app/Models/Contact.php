<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'subject',
        'message',
        'property_id',
        'agent_id',
        'is_read',
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    /**
     * The "booted" method of the model.
     * Danışmanlar sadece kendilerine gelen mesajları görecek şekilde global scope ekler
     */
    protected static function booted()
    {
        static::addGlobalScope('agentContacts', function (Builder $builder) {
            if (Auth::check() && Auth::user()->isAgent()) {
                $builder->where('agent_id', Auth::user()->agent_id);
            }
        });
    }

    public function property(): BelongsTo
    {
        return $this->belongsTo(Property::class);
    }
    
    public function agent(): BelongsTo
    {
        return $this->belongsTo(Agent::class);
    }
}
