<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsSetting extends Model
{
    protected $fillable = ['church_id', 'key', 'value'];

    protected $casts = [
        'value' => 'array',
    ];
}
