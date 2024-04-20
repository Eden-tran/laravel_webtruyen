<?php

use App\Models\Group;
use App\Models\Bookmark;
use App\Models\Category;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

function getAllCate()
{
    $cate = new Category();
    return $cate->where('active', '=', '2')->get();
}
function getAllGroup()
{
    // $group = new Group;
    if (Auth::user()->group_id == 1) {
        $data = Group::all();
    } else {
        $data = Group::where('id', '!=', 1)->get();
    }
    return $data;
}
function isRole($dataArr = [], $action, $role = 'view')
{
    // dd($dataArr);
    if (in_array($action, $dataArr)) {
        return true;
    }
    // if (!empty($dataArr[$moduleName])) {
    //     $roleArr = $dataArr[$moduleName];
    //     if (!empty($roleArr) && in_array($role, $roleArr)) {
    //         return true;
    //     }
    // }
    return false;
}
function getBookmarks()
{
    if (Auth::user()) {
        $bookMarks = Bookmark::where('user_id', Auth::user());
    }
    return $bookMarks;
}
function paginate($items, $perPage = 5, $page = null)
{
    $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
    $items = $items instanceof Collection ? $items : Collection::make($items);
    return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, [
        'path' => Paginator::resolveCurrentPath(),
        'pageName' => 'page',
    ]);
}
function getScope($module)
{
    if ($module) {
        if (Auth::user()) {

            $scope = Auth::user()->group->modules()->where('name', $module)->firstOrFail()->pivot->scope;
            return $scope;
        }
    }
}