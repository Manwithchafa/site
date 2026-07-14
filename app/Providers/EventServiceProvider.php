<?php

namespace App\Providers;

use App\Events\VisitorRegistered;
use App\Listeners\QueueWelcomeSmsListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        VisitorRegistered::class => [
            QueueWelcomeSmsListener::class,
        ],
    ];
}
