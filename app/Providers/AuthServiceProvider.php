<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Group;
use App\Models\Manga;
use App\Models\Module;
use App\Models\Chapter;
use App\Models\Category;
use App\Policies\UserPolicy;
use App\Policies\GroupPolicy;
use App\Policies\MangaPolicy;
use App\Policies\ChapterPolicy;
use App\Policies\CategoryPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        Group::class => GroupPolicy::class,
        Category::class => CategoryPolicy::class,
        Manga::class => MangaPolicy::class,
        User::class => UserPolicy::class,
        Chapter::class => ChapterPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        try {
            $moduleList = Module::with('actions')->get();

            if ($moduleList->count() > 0) {
                foreach ($moduleList as $module) {
                    foreach ($module->actions as $action) {
                        Gate::define(strtolower($module->name . '.' .  $action->name), function (User $user) use ($action, $module) {
                            if ($user->group_id == null) { //! normal user with group_id==null => only acces edit $user information
                                return false;
                            }
                            if ($user->group_id == 1) { //! isSuperadmin pass all define
                                return true;
                            }
                            if ($user->group->active != 2) { //! if group isn't active cannot access to any module
                                return false;
                            }
                            if ($user->active == 1) { //! user no active
                                return false;
                            }
                            $userActionList = $user->group->actions->pluck('id')->toArray();
                            if (!empty($userActionList)) { // check if role can use
                                if (isRole($userActionList, $action->id,)) {
                                    $check = isRole($userActionList, $action->id);
                                } else {
                                    $check = false;
                                }
                                return $check;
                            }
                            return false;
                        });
                    }
                }
            }
        } catch (QueryException $e) {
            return false;
        }
        // try {
        //     $moduleList = Module::all();

        //     if ($moduleList->count() > 0) {
        //         $actionArr = [
        //             'View' => 'Xem',
        //             'Add' => 'Thêm',
        //             'Edit' => 'Sửa',
        //             'Delete' => 'Xóa',
        //         ];
        //         foreach ($actionArr as $key => $value) {
        //             foreach ($moduleList as $module) {
        //                 Gate::define(strtolower($module->name . '.' . $key), function (User $user) use ($key, $module) {
        //                     if ($user->group_id == null) { //! normal user with group_id==null => only acces edit $user information
        //                         return false;
        //                     }
        //                     if ($user->group_id == 1) { //! isSuperadmin pass all define
        //                         return true;
        //                     }
        //                     if ($user->group->active != 2) { //! if group isn't active cannot access to any module
        //                         return false;
        //                     }
        //                     $roleJson = $user->group->permissions;
        //                     if (!empty($roleJson)) { // check if role can use
        //                         $roleArr = json_decode($roleJson, true);
        //                         if (isRole($roleArr, $module->name, 'View')) {
        //                             $check = isRole($roleArr, $module->name, $key);
        //                         } else {
        //                             $check = false;
        //                         }
        //                         return $check;
        //                     }
        //                     return false;
        //                 });
        //             }
        //         }
        //         Gate::define('group.decentralize', function (User $user) {
        //             if ($user->group_id == 1) { // isSuperadmin pass all define
        //                 return true;
        //             }
        //             if ($user->group->active == 1) { //! if group isn't active cannot access to any module
        //                 return false;
        //             }
        //             $roleJson = $user->group->permissions;
        //             if (!empty($roleJson)) { // check if role can use
        //                 $roleArr = json_decode($roleJson, true);
        //                 if (isRole($roleArr, 'Group', 'View')) {
        //                     $check = isRole($roleArr, 'Group', 'Decentralize');
        //                 } else {
        //                     $check = false;
        //                 }
        //                 return $check;
        //             }
        //             return false;
        //         });
        //     }
        // } catch (QueryException $e) {
        //     return false;
        // }
    }
}