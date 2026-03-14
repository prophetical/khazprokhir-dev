<?php

namespace App\Policies;

use App\Models\User;

class PackPolicy
{
    /**
     * Determine whether the user can select/create packs.
     */
    public function create(User $user): bool
    {
        return in_array($user->role, ['sortir', 'admin']);
    }

    /**
     * Determine whether the user can view packs (for dashboard/reports)
     */
    public function viewAny(User $user): bool
    {
        return true; // We can restrict this further based on route if needed
    }
}
