<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VisitorRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'public_uuid',
        'visitor_id',
        'church_id',
        'church_service_id',
        'qr_code_id',
        'registered_on',
        'registered_at',
        'prayer_request',
        'ip_address',
        'user_agent',
    ];

    protected function casts(): array
    {
        return [
            'registered_on' => 'date',
            'registered_at' => 'datetime:H:i:s',
        ];
    }

    public function visitor(): BelongsTo
    {
        return $this->belongsTo(Visitor::class);
    }

    public function church(): BelongsTo
    {
        return $this->belongsTo(Church::class);
    }

    public function qrCode(): BelongsTo
    {
        return $this->belongsTo(QrCode::class);
    }
}
