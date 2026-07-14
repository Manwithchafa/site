<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Audit extends Model
{
    protected $fillable = ['user_id', 'auditable_type', 'auditable_id', 'event', 'old_values', 'new_values', 'ip'];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];
}
