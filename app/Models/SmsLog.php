<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SmsLog extends Model
{
    protected $fillable = [
        'church_id', 'visitor_id', 'phone', 'message', 'template_id', 'status', 'error', 'attempts', 'external_id', 'sent_at'
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    public function template()
    {
        return $this->belongsTo(SmsTemplate::class, 'template_id');
    }

    public function visitor()
    {
        return $this->belongsTo(Visitor::class);
    }
}
