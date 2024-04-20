<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Manga;
use App\Models\Chapter;
use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\HandlesAuthorization;

class ChapterPolicy
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
        $this->scope = getScope('Chapter');
    }
    public function viewAny(User $user,  Manga $manga)
    {
        if ($this->scope == 1) {
            return true;
        }
        return $manga->user_id == $user->id;

        $res = $manga->chapters->filter(function ($chapter) use ($user) {
            return $chapter->user_id == $user->id;
        });
        if (count($res) > 0) {
            return true;
        }
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Chapter  $chapter
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Chapter $chapter)
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
        return Gate::allows('chapter.add');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Chapter  $chapter
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Chapter $chapter)
    {
        if ($this->scope == 1) {
            return true;
        }
        return $chapter->user_id == $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Chapter  $chapter
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Chapter $chapter)
    {
        //
        if ($this->scope == 1) {
            return true;
        }
        return $chapter->user_id == $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Chapter  $chapter
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Chapter $chapter)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Chapter  $chapter
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Chapter $chapter)
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