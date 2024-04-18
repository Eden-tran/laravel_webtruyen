<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Group;
use App\Models\Action;
use App\Models\Module;
use Illuminate\Http\Request;
use App\Policies\GroupPolicy;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class GroupController extends Controller
{
    public function index()
    {
        $perPage = 10;
        $module = 'Group';
        $scope = json_decode(Auth::user()->group->permissions, true)[$module]['Scope'];
        if ($scope == 1) {
            $list = Group::paginate($perPage);
        } else {
            $list = Group::where('user_id', '=', Auth::user()->id)->paginate($perPage);
        }
        $title = 'Nhóm người dùng';
        return view('backend.group.list', compact('list', 'title'));
    }
    public function getAdd()
    {
        $this->authorize('create', Group::class);
        $title = 'Thêm nhóm người dùng';
        $modules = Module::whereHas('actions')->get();
        // $jsModuleHandle = [];
        // foreach ($modules as $module) {
        //     $jsModuleHandle[$module->name] = $module->actions->pluck('name')->toArray(); // Extract image IDs
        // }
        // if (old()) {
        //     dd(old());
        // }
        // $roleArr = [
        //     'View' => 'Xem',
        //     'Add' => 'Thêm',
        //     'Edit' => 'Sửa',
        //     'Delete' => 'Xóa',
        // ];
        return view('backend.group.addForm', compact('title', 'modules',));
    }
    public function postAdd(Request $request)
    {
        $this->authorize('create', Group::class);
        $request->validate([
            'txtGroupName' => 'required|max:30|unique:groups,name',
            'txtGroupDescribe' => 'max:255',
            'slGroupStatus' => 'required',
            'role' => ['required', function ($attribute, $value, $fail) use ($request) {
                $modulesFail = [];
                if ($request->scope) {
                    $modulesScopeList = array_keys($request->scope);
                    $modulesList = Action::find($value)->pluck('module_id')->unique()->toArray();
                    $diff = array_merge(
                        array_diff($modulesList, $modulesScopeList),
                        array_diff($modulesScopeList, $modulesList,)
                    );
                    $modulesFail = Module::findMany($diff)->pluck('name')->toArray();
                    if (!empty($modulesFail)) {
                        $modulesFail = implode(" , ", $modulesFail);
                        $fail('Phân quyền ' . $modulesFail . ' không có phạm vi truy cập');
                    }
                } else {
                    $fail('Scope không được bỏ trống');
                }
            }],
            'scope' => [function ($attribute, $value, $fail) use ($request) {
                $modulesFail = [];
                $actions = $request->role;
                if ($actions) {
                    $modulesList = Action::find($actions)->pluck('module_id')->unique()->toArray();
                    $modulesScopeList = array_keys($value);
                    $diff = array_merge(
                        array_diff($modulesList, $modulesScopeList),
                        array_diff($modulesScopeList, $modulesList,)
                    );
                    $modulesFail = Module::findMany($diff)->pluck('name')->toArray();
                    if (!empty($modulesFail)) {
                        $modulesFail = implode(" , ", $modulesFail);
                        $fail('Phân quyền ' . $modulesFail . ' không hợp lệ.');
                    }
                }
            }],
        ], [
            'txtGroupName.required' => ':attribute không được bỏ trống',
            'txtGroupName.max' => ':attribute phải tối đa :max ký tự',
            'txtGroupName.unique' => ':attribute đã tồn tại',
            'txtGroupDescribe.max' => ':attribute phải tối đa :max ký tự',
            'slGroupStatus.required' => ':attribute không được bỏ trống',
            'role.required' => ':attribute không được bỏ trống'

        ], [
            'txtGroupName' => 'Tên nhóm',
            'txtGroupDescribe' => 'Miêu tả nhóm',
            'slGroupStatus' => 'Trạng thái nhóm',
            'role' => 'Phân quyền'

        ]);
        $actionArr = $request->role;
        //  trường hợp chỉ tồn tại mỗi giá trị của scope thì xóa, phải tồn tại action+scope
        // $roleJson = json_encode($roleArr);
        $group = new Group;
        $group->name = $request->txtGroupName;
        $group->describe = $request->txtGroupDescribe;
        $group->active = $request->slGroupStatus;
        $group->permissions = " ";
        $group->user_id = Auth::user()->id;

        $group->save();
        if ($group->id) {
            $group->actions()->attach($actionArr);
            $scopeData = collect($request->scope)->map(function ($value, $key) {
                return ['scope' => $value];
            });
            $group->modules()->attach($scopeData);

            return redirect()->route('admin.group.list')->with('msg', 'thêm thành công');
        } else {
            return redirect()->route('admin.group.list')->with('msg', 'Đã xảy ra lỗi');
        }
    }
    public function getEdit(Group $group)
    {
        $this->authorize('update', $group);
        if ($group->id == 1 || $group->id == 2) {
            return redirect()->route('admin.group.list')->with('msg', 'Đây là nhóm mặc định không được sửa');
        }
        $title = 'Sửa nhóm người dùng';
        $modules = Module::whereHas('actions')->get();
        // dd($modules->pluck('id')->toArray());
        // dd(array_key_exists(2, $group->modules->pluck('pivot.scope', 'id')->toArray()));
        // dd($group->modules->pluck('pivot.scope', 'id')->toArray());
        // dd($group->modules()->where('module_id', 1)->get()->first()->pivot->scope);
        return view('backend.group.editForm', compact('title', 'group', 'modules',));
    }
    public function postEdit(Group $group, Request $request)
    {
        // dd($request->all());
        $this->authorize('update', $group);
        if ($group->id == 1 || $group->id == 2) {
            return redirect()->route('admin.group.list')->with('msg', 'Đây là nhóm mặc định không được sửa');
        }
        $rules = [
            'txtGroupName' => 'required|max:30|unique:groups,name,' . $group->id,
            'txtGroupDescribe' => 'max:255',
            'slGroupStatus' => 'required'
        ];
        $messages = [
            'txtGroupName.required' => ':attribute không dược bỏ trống',
            'txtGroupName.max' => ':attribute phải tối đa :max ký tự',
            'txtGroupName.unique' => ':attribute đã tồn tại',
            'txtGroupDescribe.max' => ':attribute phải tối đa :max ký tự',
            'slGroupStatus.required' => ':attribute không dược bỏ trống',
        ];
        $attributes = [
            'txtGroupName' => 'Tên nhóm',
            'txtGroupDescribe' => 'Miêu tả nhóm',
            'slGroupStatus' => 'Trạng thái nhóm',
        ];
        if (Gate::allows('group.decentralize')) {

            $rules['role'] = ['required', function ($attribute, $value, $fail) use ($request) {
                $modulesFail = [];
                if ($request->scope) {
                    $modulesScopeList = array_keys($request->scope);
                    $modulesList = Action::find($value)->pluck('module_id')->unique()->toArray();
                    $diff = array_merge(
                        array_diff($modulesList, $modulesScopeList),
                        array_diff($modulesScopeList, $modulesList,)
                    );
                    $modulesFail = Module::findMany($diff)->pluck('name')->toArray();
                    if (!empty($modulesFail)) {
                        $modulesFail = implode(" , ", $modulesFail);
                        $fail('Phân quyền ' . $modulesFail . ' không có phạm vi truy cập');
                    }
                } else {
                    $fail('Scope không được bỏ trống');
                }
            }];
            $rules['scope'] = [function ($attribute, $value, $fail) use ($request) {
                $modulesFail = [];
                $actions = $request->role;
                if ($actions) {
                    $modulesList = Action::find($actions)->pluck('module_id')->unique()->toArray();
                    $modulesScopeList = array_keys($value);
                    $diff = array_merge(
                        array_diff($modulesList, $modulesScopeList),
                        array_diff($modulesScopeList, $modulesList,)
                    );
                    $modulesFail = Module::findMany($diff)->pluck('name')->toArray();
                    if (!empty($modulesFail)) {
                        $modulesFail = implode(" , ", $modulesFail);
                        $fail('Phân quyền ' . $modulesFail . ' không hợp lệ.');
                    }
                }
            }];
            $messages['role.required'] = ':attribute không được bỏ trống';
            $attributes['role'] = 'Phân quyền';
        };
        $request->validate($rules, $messages, $attributes);
        // if ($request->role) {
        //     $roleArr = $request->role;
        //     $verifiedRole = array_filter($roleArr, function ($item) {
        //         return count($item) > 1 || !array_key_exists('Scope', $item);
        //     });
        //     $roleJson = json_encode($verifiedRole);
        //     $group->permissions = $roleJson;
        // }
        $actionArr = $request->role;
        $group->name = $request->txtGroupName;
        $group->describe = $request->txtGroupDescribe;
        $group->active = $request->slGroupStatus;
        if ($group->save()) {
            $group->actions()->sync($actionArr);
            $scopeData = collect($request->scope)->map(function ($value, $key) {
                return ['scope' => $value];
            });
            $group->modules()->sync($scopeData);
            return redirect()->route('admin.group.list')->with('msg', 'sửa thành công');
        } else {
            return redirect()->route('admin.group.list')->with('msg', 'Đã xảy ra lỗi');
        }
    }
    public function delete(Group $group)
    {
        $this->authorize('delete', $group);
        if ($group->id == 1 || $group->id == 2) {
            return redirect()->route('admin.group.list')->with('msg', 'Đây là nhóm mặc định không được xóa');
        }
        $userCount = $group->users->count();
        if ($userCount == 0) {
            Group::destroy($group->id);
            return redirect()->route('admin.group.list')->with('msg', 'Xóa nhóm thành công');
        } else {
            return redirect()->route('admin.group.list')->with('msg', 'Nhóm còn tồn tại người dùng');
        }
    }
}
