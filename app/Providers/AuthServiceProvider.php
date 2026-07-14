<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Visitor;
use App\Policies\VisitorPolicy;
use App\Models\FollowUp;
use App\Policies\FollowUpPolicy;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        Visitor::class => VisitorPolicy::class,
        FollowUp::class => FollowUpPolicy::class,
    ];

    public function boot(): void
    {
        $this->registerPolicies();

        // Example global gate for super-admin role (implement role logic in User)
        Gate::before(function ($user, $ability) {
            if (method_exists($user, 'isSuperAdmin') && $user->isSuperAdmin()) {
                return true;
            }
        });
    }
}
