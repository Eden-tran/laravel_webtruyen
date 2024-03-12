<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Group;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Access\HandlesAuthorization;

class GroupPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    protected $scope;
    public function __construct(User $user)
    {
        $module = 'Group';
        $this->scope = json_decode(Auth::user()->group->permissions, true)[$module]['Scope'];;
    }
    public function viewAny(User $user)
    {
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(Group $group, User $user)
    {
        if ($this->scope == 1) {
            return true;
        }
        return $group->user_id === $user->id;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {

        $key = 'Add';
        $moduleName = 'Group';
        $roleJson = $user->group->permissions;
        if (!empty($roleJson)) { // check if role can use
            $roleArr = json_decode($roleJson, true);
            $check = isRole($roleArr, $moduleName, $key);
            return $check;
        }
        return false;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Group $group)
    {
        if ($this->scope == 1) {
            return true;
        }
        return $user->id === $group->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Group $group)
    {

        if ($group->id == 1) {
            $check = false;
        } else {
            if ($user->id === $group->user_id) {
                $check = true;
            }
            if ($this->scope == 1) {
                return true;
            }
        }
        return $check;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Group $group)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Group  $group
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Group $group)
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
