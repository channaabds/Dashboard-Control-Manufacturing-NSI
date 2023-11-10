<?php

namespace App\Policies;

use App\Models\Target;
use App\Models\UserTest;
use Illuminate\Auth\Access\Response;

class TargetPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(UserTest $userTest): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(UserTest $userTest, Target $target): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(UserTest $userTest): bool
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(UserTest $userTest, Target $target): bool
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(UserTest $userTest, Target $target): bool
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(UserTest $userTest, Target $target): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(UserTest $userTest, Target $target): bool
    {
        //
    }
}
