<?php

namespace App\Policies;

use App\Models\Skymonitor;
use App\Models\User;

class SkymonitorPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Skymonitor $skymonitor): bool
    {
        return $user->id === $skymonitor->user_id;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Skymonitor $skymonitor): bool
    {
        return $user->id === $skymonitor->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Skymonitor $skymonitor): bool
    {
        return $user->id === $skymonitor->user_id;
    }
}
