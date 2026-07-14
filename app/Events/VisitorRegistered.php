<?php

namespace App\Events;

use App\Models\VisitorRegistration;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class VisitorRegistered
{
    use Dispatchable, SerializesModels;

    public VisitorRegistration $registration;

    public function __construct(VisitorRegistration $registration)
    {
        $this->registration = $registration;
    }
}
