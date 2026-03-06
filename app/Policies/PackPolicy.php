<?php

namespace App\Policies;

use App\Models\Pack;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PackPolicy
{
    /**
     * Determine whether the user can select/create packs.
     */
    public function create(User $user): bool
    {
        return $user->role === 'sortir';
    }

    /**
     * Determine whether the user can view packs (for dashboard/reports)
     */
    public function viewAny(User $user): bool
    {
        return true; // We can restrict this further based on route if needed
    }
}
