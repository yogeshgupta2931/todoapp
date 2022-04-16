<?php

namespace App\Policies;

use App\Models\Todo;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use App\Enums\UserRoleEnum;

class TodoPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\todo  $todo
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, todo $todo)
    {
        //
        if ($user->role_id == UserRoleEnum::Admin) {
            return true;
        } else {
            return $user->id == $todo->user_id;
        }
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        //
    }
    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Todo  $todo
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Todo $todo)
    {
        // If logged in user role is Admin, then allow them to update any TODO
        // else only allow owner of todo to update it
        if ($user->role_id == UserRoleEnum::Admin) {
            return true;
        } else {
            return $user->id == $todo->user_id;
        }
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Todo  $todo
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Todo $todo)
    {
        // If logged in user role is Admin, then allow them to delete any TODO
        // else only allow owner of todo to delete it
        if ($user->role_id == UserRoleEnum::Admin) {
            return true;
        } else {
            return $user->id == $todo->user_id;
        }
    }

    public function restore(User $user, Todo $todo)
    {
        // If logged in user role is Admin, then allow them to delete any TODO
        // else only allow owner of todo to delete it
        if ($user->role_id == UserRoleEnum::Admin) {
            return true;
        } else {
            return $user->id == $todo->user_id;
        }
    }
}
