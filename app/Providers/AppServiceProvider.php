<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Observers\AuditableObserver;
use App\Models\Visitor;
use App\Models\FollowUp;
use App\Models\VisitorRegistration;
use App\Models\VisitorNote;
use App\Models\VisitorAssignment;
use App\Models\SmsTemplate;
use App\Models\SmsSetting;
use App\Models\Church;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register model observers for audit/activity logging
        $observer = new AuditableObserver();

        Visitor::observe($observer);
        FollowUp::observe($observer);
        VisitorRegistration::observe($observer);
        VisitorNote::observe($observer);
        VisitorAssignment::observe($observer);
        SmsTemplate::observe($observer);
        SmsSetting::observe($observer);

        // Share the current (default) church with all views so UI uses dynamic church data
        try {
            $currentChurch = Church::query()->where('status', 'active')->first();
            View::share('currentChurch', $currentChurch);
        } catch (\Throwable $e) {
            // Non-fatal — ensure app still boots
        }
    }
}
