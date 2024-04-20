<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Group;
use App\Models\Action;
use App\Models\Module;
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
    protected $moduleId;

    public function __construct(User $user)
    {
        $module = 'Group';
        $this->moduleId = Module::where('Name', '=', class_basename(Group::class))->firstOrFail()?->id;
        $this->scope =  Auth::user()->group->modules()->where('module_id',   $this->moduleId)->firstOrFail()->pivot->scope;
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
        $actionName = 'Add';
        $actionId = Action::where([
            ['module_id', $this->moduleId],
            ['name', $actionName],
        ])->firstOrFail()->id;
        $userActionList = $user->group->actions->pluck('id')->toArray();
        if (!empty($userActionList)) { // check if role can use
            if (isRole($userActionList, $actionId)) {
                $check = isRole($userActionList, $actionId);
            } else {
                $check = false;
            }
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
        return false;
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