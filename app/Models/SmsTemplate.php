<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsTemplate extends Model
{
    protected $fillable = ['church_id', 'name', 'slug', 'body'];

    public function church()
    {
        return $this->belongsTo(Church::class);
    }
}
