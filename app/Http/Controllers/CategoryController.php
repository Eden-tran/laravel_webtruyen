<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function index()
    {
        $perPage = 10;

        $scope = getScope('Category');
        if ($scope == 1) {
            $list = Category::paginate($perPage);
        } else {
            $list = Category::where('user_id', '=', Auth::id())->paginate($perPage);
        }
        $title = 'Danh sách danh mục';
        return view('backend.category.list', compact('title', 'list'));
    }
    public function getCreate()
    {
        $this->authorize('create', Category::class);
        $title = 'Thêm danh mục';

        return view('backend.category.addForm', compact('title'));
    }
    public function postCreate(Request $request)
    {
        $this->authorize('create', Category::class);

        $request->validate(
            [
                'txtCateName' => 'required|max:30|unique:categories,name',
                'txtCateSlug' => 'required|max:40|alpha_dash|unique:categories,name',
                'txtCateDescribe' => 'required|max:255',
                'slCateStatus' => 'required'
            ],
            [
                'txtCateName.required' => ':attribute không dược bỏ trống',
                'txtCateName.max' => ':attribute phải tối đa :max ký tự ',
                'txtCateName.unique' => ':attribute đã tồn tại',
                'txtCateSlug.required' => ':attribute không được bỏ trống',
                'txtCateSlug.max' => ':attribute phải tối thiểu :max ký tự ',
                'txtCateSlug.alpha_dash' => ':attribute không đúng định dạng ',
                'txtCateSlug.unique' => ':attribute đã tồn tại',
                'txtCateDescribe.required' => ':attribute không dược bỏ trống',
                'txtCateDescribe.max' => ':attribute phải tối đa :max ký tự ',
                'slCateStatus.required' => ':attribute không được bỏ trống',
            ],
            [
                'txtCateName' => 'Tên danh mục',
                'txtCateSlug' => 'Slug',
                'txtCateDescribe' => 'Miêu tả danh mục',
                'slCateStatus' => 'Trạng thái',
            ]
        );
        $category = new Category;
        $category->name = $request->txtCateName;
        $category->slug = $request->txtCateSlug;
        $category->user_id = Auth::user()->id;
        $category->description = $request->txtCateDescribe;
        $category->active = $request->slCateStatus;
        $category->save();
        // dd($category->id);
        if ($category->id) {
            return redirect()->route('admin.category.list')->with('msg', 'thêm thành công');
        } else {
            return redirect()->route('admin.category.getAdd')->with('msg', 'Đã xảy ra lỗi');
        }
    }
    public function getEdit(Category $category)
    {
        $this->authorize('update', $category);
        $title = 'Sửa danh mục';
        return view('backend.category.editForm', compact('title', 'category'));
    }
    public function postEdit(request $request, Category $category)
    {
        $this->authorize('update', $category);

        $name = $category->name;
        $id = $category->id;
        $request->validate(
            [
                'txtCateName' => "required|max:30|unique:categories,name,$id",
                'txtCateSlug' => "required|max:40|alpha_dash|unique:categories,slug,$id",
                'txtCateDescribe' => 'required|max:255',
                'slCateStatus' => 'required'
            ],
            [
                'txtCateName.required' => ':attribute không dược bỏ trống',
                'txtCateName.max' => ':attribute phải tối thiểu :max ký tự ',
                'txtCateName.unique' => ':attribute đã tồn tại',
                'txtCateSlug.required' => ':attribute không được bỏ trống',
                'txtCateSlug.max' => ':attribute phải tối thiểu :max ký tự ',
                'txtCateSlug.alpha_dash' => ':attribute không đúng định dạng ',
                'txtCateSlug.unique' => ':attribute đã tồn tại',
                'txtCateDescribe.required' => ':attribute không dược bỏ trống',
                'txtCateDescribe.max' => ':attribute phải tối thiểu :max ký tự ',
                'slCateStatus.required' => ':attribute không được bỏ trống',
            ],
            [
                'txtCateName' => 'Tên danh mục',
                'txtCateSlug' => 'Slug',
                'txtCateDescribe' => 'Miêu tả danh mục',
                'slCateStatus' => 'Trạng thái',
            ]
        );
        $category->name = $request->txtCateName;
        $category->slug = $request->txtCateSlug;
        $category->description = $request->txtCateDescribe;
        $category->active = $request->slCateStatus;
        $category->save();
        // dd($category->id);
        if ($category->id) {
            return redirect()->route('admin.category.list')->with('msg', 'sửa thành công');
        } else {
            return redirect()->route('admin.category.list')->with('msg', 'Đã xảy ra lỗi');
        }
    }
    public function delete(Category $category)
    {
        $this->authorize('delete', $category);

        if ($category) {
            // $category->delete();
            if ($category->delete()) {
                return redirect()->route('admin.category.list')->with('msg', 'xóa thành công');
            } else {
                return redirect()->route('admin.category.list')->with('msg', 'xóa thất bại');
            }
        }
    }
}
