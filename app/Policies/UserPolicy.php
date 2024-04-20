<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Module;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */

    protected $scope;
    protected $moduleId;

    public function __construct()
    {
        // $module = 'User';
        // $this->moduleId = Module::where('Name', '=', class_basename(User::class))->firstOrFail()?->id;
        $this->scope = getScope(class_basename(User::class));
        // $this->scope =  Auth::user()->group->modules()->where('module_id',   $this->moduleId)->firstOrFail()->pivot->scope;
    }
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, User $model)
    {
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
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, User $model)
    {
        // Allow user can edit all user (except super admin)
        if ($this->scope == 1) {
            return true;
        }
        // Allow users to edit themselves

        if ($user->id === $model->id) {
            return true;
        }

        // Otherwise, check if the user has the 'edit_users' permission
        return Gate::allows('user.edit');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, User $model)
    {

        if ($model->id == 1) {
            return false;
        }
        if ($model->id == $user->id) {
            return false;
        }
        if ($this->scope == 1) {
            return true;
        }
        return $user->id === $model->user_id;
    }
    public function changePassword(User $user, User $model)
    {

        // only user can change they password admin and super admin cannot change
        return $user->id === $model->id;
    }
    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, User $model)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\User  $model
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, User $model)
    {
        //
    }
    public function before(User $user)
    {
        if ($user->group_id == 1) {
            return true;
        }
    }
}