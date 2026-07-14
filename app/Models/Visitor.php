<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Visitor extends Model
{
    use HasFactory;

    protected $fillable = [
        'church_id',
        'first_name',
        'last_name',
        'sex',
        'age',
        'date_of_birth',
        'marital_status',
        'wedding_anniversary',
        'phone',
        'email',
        'city',
        'residential_address',
        'business_address',
        'nearest_bus_stop',
        'occupation',
        'invited_by',
        'invited_by_phone',
        'invited_by_name',
        'wants_membership',
        'born_again',
        'born_again_when',
        'wants_counsel',
        'preferred_visit_date',
        'is_baptized',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'wedding_anniversary' => 'date',
            'born_again_when' => 'date',
            'preferred_visit_date' => 'date',
            'born_again' => 'boolean',
            'is_baptized' => 'boolean',
            'wants_membership' => 'boolean',
            'wants_counsel' => 'boolean',
            'age' => 'integer',
        ];
    }

    public function church(): BelongsTo
    {
        return $this->belongsTo(Church::class);
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(VisitorRegistration::class);
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(VisitorAssignment::class);
    }

    public function notes(): HasMany
    {
        return $this->hasMany(VisitorNote::class);
    }

    public function getFullNameAttribute(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }
}
