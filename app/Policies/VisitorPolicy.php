<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Visitor;

class VisitorPolicy
{
    public function viewAny(User $user): bool
    {
        return true; // restrict in production by roles
    }

    public function view(User $user, Visitor $visitor): bool
    {
        return true; // implement church-based checks here
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Visitor $visitor): bool
    {
        return true;
    }

    public function delete(User $user, Visitor $visitor): bool
    {
        return false;
    }
}
