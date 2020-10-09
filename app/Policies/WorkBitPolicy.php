<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WorkBit;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class WorkBitPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\WorkBit  $workBit
     * @return mixed
     */
    public function view(User $user, WorkBit $workBit)
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->isCompany()
            ? Response::allow()
            : Response::deny('Only companies can create work bits.');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\WorkBit  $workBit
     * @return mixed
     */
    public function update(User $user, WorkBit $workBit)
    {
        return $user->is($workBit->author);
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\WorkBit  $workBit
     * @return mixed
     */
    public function delete(User $user, WorkBit $workBit)
    {
        return $user->is($workBit->author);
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\WorkBit  $workBit
     * @return mixed
     */
    public function restore(User $user, WorkBit $workBit)
    {
        return $user->is($workBit->author);
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\WorkBit  $workBit
     * @return mixed
     */
    public function forceDelete(User $user, WorkBit $workBit)
    {
        return false;
    }
}
