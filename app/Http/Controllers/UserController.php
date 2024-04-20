<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    protected $scope;
    protected $moduleId;
    public function __construct()
    {
        $this->moduleId = Module::where('Name', '=', class_basename(User::class))->firstOrFail()?->id;
    }
    public function index()
    {
        $title = 'Quản lý người dùng';
        $user = Auth::user();
        $scope = $this->scope = getScope(class_basename(User::class));
        $perPage = 10;
        if ($scope == 1) {
            $list = User::orderBy('group_id')->paginate($perPage);
        } else {
            $id = $user->id;
            $list = User::where('user_id', '=', $id)->orderBy('group_id')
                ->paginate($perPage);
        };
        return view('backend.user.list', compact('title', 'list'));
    }
    public function getAdd()
    {
        $title = 'Thêm người dùng';
        return view('backend.user.addForm', compact('title'));
    }
    public function postAdd(Request $request)
    {
        $request->validate([
            'txtUserName' => 'required|min:5',
            'txtUserEmail' => 'required|email|unique:users,email',
            'txtUserPassword' => 'required|confirmed|min:8',
            'txtUserPassword_confirmation' => 'required|min:8',
            'slUserGroup' => [
                'required', function ($attribute, $value, $fail) use ($request) {
                    if ($value == 1 && $request->user()->group_id != 1) {
                        $fail('Bạn không có quyền tạo ra super admin');
                    }
                }

            ],
            'slUserActive' => 'required',
            'imgCover' => 'image|mimes:jpg,png,jpeg|max:2048',
        ], [
            // 'txtUserName.required' => ':attribute không được bỏ trống',
            'txtUserName.min' => ':attribute phải có ít nhất :min ký tự',
            'txtUserEmail.required' => ':attribute không được bỏ trống',
            'txtUserEmail.email' => ':attribute không phải định dạng email',
            'txtUserEmail.unique' => ':attribute đã tồn tại',
            'txtUserPassword.required' => ':attribute không được bỏ trống',
            'txtUserPassword.confirmed' => ':attribute không trùng khớp',
            'txtUserPassword.min' => ':attribute phải có ít nhất :min ký tự',
            'txtUserPassword_confirmation.required' => ':attribute không được bỏ trống',
            'txtUserPassword_confirmation.min' => ':attribute phải có ít nhất :min ký tự',
            'slUserGroup.required' => ':attribute chưa chọn',
            'slUserActive.required' => ':attribute chưa chọn',
            'imgCover.required' => ':attribute chưa chọn',
            'imgCover.image' => ':attribute không đúng định dạng',
            'imgCover.mimes' => ':attribute không đúng định dạng',
            'imgCover.max' => ':attribute quá lớn',
        ], [
            'txtUserName' => 'Tên người dùng',
            'txtUserEmail' => 'Email',
            'txtUserPassword' => 'Mật khẩu',
            'txtUserPassword_confirmation' => 'Xác nhận mật khẩu',
            'slUserGroup' => 'Nhóm người dùng',
            'slUserActive' => 'Trạng thái người dùng',
            'imgCover' => 'Avatar',
        ]);

        $user = new User();
        $user->name = $request->txtUserName;
        $user->email = $request->txtUserEmail;
        $user->password = Hash::make($request->txtUserPassword);
        $user->group_id = $request->slUserGroup;
        $user->active = $request->slUserActive;
        $user->email_verified_at = now();
        $user->user_id = Auth::id();
        // upload avatar;
        // $image = $request->file('imgCosver');
        // Generate a unique name for the image.
        if ($request->file('imgCover')) {
            $extension = pathinfo($request->file('imgCover')->getClientOriginalName(), PATHINFO_EXTENSION);
            $uniqueName = uniqid() . '.' . $extension;
            $store = $request->file('imgCover')->storeAs('avatar', $uniqueName, 'public');
            $imgName = basename($store);
        } else {
            $imgName = 'default.jpg';
        }
        $user->avatar = $imgName;
        $user->save();
        if ($user->id) {
            return redirect()->route('admin.user.list')->with('msg', 'thêm người dùng thành công');
        } else {
            return redirect()->route('admin.user.list')->with('msg', 'Đã xảy ra lỗi');
        }
    }
    public function getEdit(Request $request, User $user)
    {
        $this->authorize('update', $user);
        $title = 'Sửa người dùng';
        return view('backend.user.editForm', compact('title', 'user'));
    }

    public function postEdit(Request $request, User $user)
    {
        $this->authorize('update', $user);
        $request->validate([
            'txtUserName' => 'required|min:5',
            // 'txtUserEmail' => 'required|email|unique:users,email,' . $user->id,
            // 'slUserGroup' => 'required',
            // 'slUserActive' => 'required',
        ], [
            'txtUserName.required' => ':attribute không được bỏ trống',
            'txtUserName.min' => ':attribute phải có ít nhất :min ký tự',
            // 'slUserGroup.required' => ':attribute chưa chọn',
            // 'slUserActive.required' => ':attribute chưa chọn',
            // 'txtUserEmail.required' => ':attribute không được bỏ trống',
            // 'txtUserEmail.email' => ':attribute không phải định dạng email',
            // 'txtUserEmail.unique' => ':attribute đã tồn tại',
        ], [
            'txtUserName' => 'Tên người dùng',
            // 'txtUserEmail' => 'Email',
            // 'slUserGroup' => 'Nhóm người dùng',
            // 'slUserActive' => 'Trạng thái người dùng',
        ]);
        $user->name = $request->txtUserName;
        // $user->email = $request->txtUserEmail;
        $superAdmin = Auth::user();
        if (Gate::allows('user.edit')) {
            $request->validate([
                'slUserGroup' => 'required',
                'slUserActive' => 'required',
            ], [
                'slUserGroup.required' => ':attribute chưa chọn',
                'slUserActive.required' => ':attribute chưa chọn',
            ], [
                'slUserGroup' => 'Nhóm người dùng',
                'slUserActive' => 'Trạng thái người dùng',
            ]);
            $user->group_id = $request->slUserGroup;
            $user->active = $request->slUserActive;
        }

        // upload avatar;
        // $image = $request->file('imgCover');
        if ($request->file('imgCover')) {
            $request->validate([
                'imgCover' => 'required|image|mimes:jpg,png,jpeg|max:2048',
            ], [
                'imgCover.required' => ':attribute chưa chọn',
                'imgCover.image' => ':attribute không đúng định dạng',
                'imgCover.mimes' => ':attribute không đúng định dạng',
                'imgCover.max' => ':attribute quá lớn',
            ], [
                'imgCover' => 'Avatar',
            ]);
            // Generate a unique name for the image.
            $extension = pathinfo($request->file('imgCover')->getClientOriginalName(), PATHINFO_EXTENSION);
            $uniqueName = uniqid() . '.' . $extension;
            $store = $request->file('imgCover')->storeAs('avatar', $uniqueName, 'public');
            if ($store) {
                $imgName = basename($store);
                if (Storage::exists('public/avatar/' . $user->avatar) && $user->avatar != 'default.jpg') {
                    Storage::delete('public/avatar/' . $user->avatar);
                } else {
                    //return false
                }
            } else {
                $imgName = 'default.jpg';
            }
            $user->avatar = $imgName;
        }
        $user->save();
        if ($user->id) {
            return redirect()->route('admin.user.list', $user->id)->with('msg', 'Sửa thông tin người dùng thành công');
        } else {
            return redirect()->route('admin.user.getEdit', $user->id)->with('msg', 'Đã xảy ra lỗi');
        }
    }
    public function delete(User $user)
    {
        $this->authorize('delete', $user);
        if ($user) {
            if ($user->id === 1) {
                return redirect()->route('admin.user.list')->with('msg', 'xóa thất bại');
            }
            if ($user->delete()) {
                if (Storage::exists('public/avatar/' . $user->avatar) && $user->avatar != 'default.jpg') {
                    Storage::delete('public/avatar/' . $user->avatar);
                }
                return redirect()->route('admin.user.list')->with('msg', 'xóa thành công');
            }
        }
        return redirect()->route('admin.user.list')->with('msg', 'xóa thất bại');
    }
    public function getChangePassword(User $user)
    {
        $this->authorize('changePassword', $user);
        $title = 'Đổi mật khẩu';
        return view('backend.user.changePassword', compact('title'));
    }
    public function postChangePassword(User $user, Request $request)
    {
        $this->authorize(
            'changePassword',
            $user
        );
        $request->validate([
            'pwPasswordOld' => ['required', function ($attribute, $value, $fail) use ($request) {
                $userPassword = Auth::user()->password;
                $attribute = 'Mật khẩu hiện tại';
                if (!Hash::check($value, $userPassword)) {
                    return $fail($attribute . ' không đúng');
                }
            }],
            'pwPasswordNew' => ['required', 'confirmed', 'min:8', function ($attribute, $value, $fail) use ($request) {
                $userPassword = Auth::user()->password;
                if (Hash::check($value, $userPassword)) {
                    // return $fail($attribute . ' không đúng');
                    $fail('Bạn đã sử dụng mật khẩu này');
                }
            }],
            'pwPasswordNew_confirmation' => 'required',
        ], [
            'pwPasswordOld.required' => ':attribute không được bỏ trống',
            'pwPasswordNew.required' => ':attribute không được bỏ trống',
            'pwPasswordNew_confirmation.required' => ':attribute không được bỏ trống',
            'pwPasswordNew.min' => ':attribute phải có tối thiểu :min ký tự',
            'pwPasswordNew.confirmed' => ':attribute xác nhận sai',

        ], [
            'pwPasswordOld' => 'Mật khẩu hiện tại',
            'pwPasswordNew' => 'Mật khẩu mới',
            'pwPasswordNew_confirmation' => 'Xác nhận mật khẩu',
        ]);
        $user->password = Hash::make($request->pwPasswordNew);

        if ($user->save()) {
            Auth::logout();
            return redirect('/admin');
        }
    }
}