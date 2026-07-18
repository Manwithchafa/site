<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class QrCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'church_id',
        'church_service_id',
        'code',
        'label',
        'status',
        'last_used_at',
    ];

    protected function casts(): array
    {
        return [
            'last_used_at' => 'datetime',
        ];
    }

    public function church(): BelongsTo
    {
        return $this->belongsTo(Church::class);
    }

    public function churchService(): BelongsTo
    {
        return $this->belongsTo(ChurchService::class);
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(VisitorRegistration::class);
    }
}
