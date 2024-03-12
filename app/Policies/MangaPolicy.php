<?php

namespace App\Policies;

use App\Models\Manga;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MangaPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    protected $scope;
    public function __construct()
    {
        $this->scope = getScope('Manga');
    }
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Manga  $manga
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Manga $manga)
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
     * @param  \App\Models\Manga  $manga
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Manga $manga)
    {

        if ($this->scope == 1) {
            return true;
        }
        return  $user->id === $manga->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Manga  $manga
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Manga $manga)
    {
        if ($this->scope == 1) {
            return true;
        }
        return  $user->id === $manga->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Manga  $manga
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Manga $manga)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Manga  $manga
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Manga $manga)
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