<?php

namespace App\Policies;

use App\Models\User;
use App\Models\FollowUp;

class FollowUpPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, FollowUp $followUp): bool
    {
        // Allow if assigned or same church in future
        return $user->id === $followUp->assigned_to;
    }

    public function update(User $user, FollowUp $followUp): bool
    {
        return $user->id === $followUp->assigned_to;
    }

    public function assign(User $user): bool
    {
        return true;
    }
}
