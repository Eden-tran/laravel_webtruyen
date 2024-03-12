<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Like;
use App\Models\Manga;
use App\Models\Comment;
use App\Models\Bookmark;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MangaController extends Controller
{
    public function index()
    {
        $perPage = 10;
        $scope = getScope('Manga');
        if ($scope == 1) {
            $mangas = Manga::paginate($perPage);
        } else {
            $mangas = Manga::where('user_id', '=', Auth::id())->paginate($perPage);
        }

        $title = 'Danh sách truyện';
        return view('backend.manga.list', compact('title', 'mangas'));
    }
    public function getCreate()
    {
        $this->authorize('create', Manga::class);

        $title = 'Thêm truyện';
        return view('backend.manga.addForm', compact('title'));
    }
    public function postCreate(Request $request)
    {
        $this->authorize('create', Manga::class);

        $request->validate(
            [
                'txtMangaName' => 'required|max:30|unique:mangas,name',
                'txtMangaAuthor' => 'required|max:40',
                'txtMangaAnotherName' => 'required|max:30|',
                'txtMangaDescribe' => 'required|max:250|',
                'slMangaCategories' => 'required',
                'slMangaStatus' => 'required',
                'txtMangaSlug' => 'required|unique:mangas,slug',
                'imgCover' => 'required|image|mimes:jpg,png,jpeg|max:2048',
            ],
            [
                'txtMangaName.required' => ':attribute không được bỏ trống',
                'slMangaCategories.required' => ':attribute không được bỏ trống',
                'txtMangaName.unique' => ':attribute đã tồn tại',
                'txtMangaName.max' => ':attribute dài tối đa :max kí tự',
                'txtMangaAuthor.required' => ':attribute không được bỏ trống',
                'txtMangaAuthor.max' => ':attribute dài tối đa :max kí tự',
                'txtMangaAnotherName.required' => ':attribute không được bỏ trống',
                'txtMangaAnotherName.max' => ':attribute dài tối đa :max kí tự',
                'txtMangaDescribe.required' => ':attribute không được bỏ trống',
                'txtMangaDescribe.max' => ':attribute dài tối đa :max kí tự',
                'slMangaStatus.required' => 'Vui lòng chọng :attribute',
                'txtMangaSlug.required' => ':attribute không được bỏ trống',
                'txtMangaSlug.unique' => ':attribute đã tồn tại',
                'imgCover.required' => ':attribute không được bỏ trống',
                'imgCover.image' => ':attribute không đúng định dạng',
                'imgCover.size' => ':attribute vượt quá dung lượng cho phép',
            ],
            [
                'txtMangaName' => 'Tên truyện',
                'txtMangaAnotherName' => 'Tên khác',
                'txtMangaAuthor' => 'Tên tác giả',
                'txtMangaDescribe' => 'Miêu tả',
                'slMangaStatus' => 'Trạng thái',
                'slMangaCategories' => 'Nhóm truyện',
                'txtMangaSlug' => 'Slug',
                'imgCover' => 'Hình bìa',
            ]
        );

        $store = $request->file('imgCover')->store('cover', 'public');
        if ($store) {
            $imgName = basename($store);
        } else {
            $imgName = 'default.jpg';
        }
        $manga = new Manga();
        $manga->name = $request->txtMangaName;
        $manga->another_name = $request->txtMangaAnotherName;
        $manga->author  = $request->txtMangaAuthor;
        $manga->describe = $request->txtMangaDescribe;
        // $manga->categories = $categories;
        $manga->user_id = Auth::user()->id;
        $manga->active = $request->slMangaStatus;
        $manga->is_finished = 2;
        $manga->slug = $request->txtMangaSlug;
        $manga->image_cover = $imgName;
        $manga->save();
        $manga->categories()->attach($request->slMangaCategories);
        if ($manga->id) {
            return redirect()->route('admin.chapter.list', [$manga->id]);
        } else {
            return redirect()->route('admin.manga.list')->with('msg', 'Đã xảy ra lỗi');
        }
    }
    public function getEdit(Manga $manga)
    {
        $this->authorize('update', $manga);
        $title = 'Sửa truyện';

        return view('backend.manga.editForm', compact('title', 'manga'));
    }
    public function postEdit(Manga $manga, Request $request)
    {
        $this->authorize('update', $manga);

        $request->validate(
            [
                'txtMangaName' => 'required|max:30|unique:mangas,name,' . $manga->id,
                'txtMangaAuthor' => 'required|max:40',
                'txtMangaAnotherName' => 'required|max:30|',
                'txtMangaDescribe' => 'required|max:250|',
                'slMangaCategories' => 'required',
                'slMangaStatus' => 'required',
                'txtMangaSlug' => 'required|unique:mangas,slug,' . $manga->id,
                'imgCover' => 'image|mimes:jpg,png,jpeg|max:2048',
            ],
            [
                'txtMangaName.required' => ':attribute không được bỏ trống',
                'txtMangaName.unique' => ':attribute đã tồn tại',
                'txtMangaName.max' => ':attribute dài tối đa :max kí tự',
                'txtMangaAuthor.required' => ':attribute không được bỏ trống',
                'txtMangaAuthor.max' => ':attribute dài tối đa :max kí tự',
                'txtMangaAnotherName.required' => ':attribute không được bỏ trống',
                'txtMangaAnotherName.max' => ':attribute dài tối đa :max kí tự',
                'txtMangaDescribe.required' => ':attribute không được bỏ trống',
                'txtMangaDescribe.max' => ':attribute dài tối đa :max kí tự',
                'slMangaCategories.required' => ':attribute không được bỏ trống',
                'slMangaStatus.required' => 'Vui lòng chọng :attribute',
                'txtMangaSlug.required' => ':attribute không được bỏ trống',
                'txtMangaSlug.unique' => ':attribute đã tồn tại',

                // 'imgCover.required' => ':attribute không được bỏ trống',
                'imgCover.image' => ':attribute không đúng định dạng',
                'imgCover.size' => ':attribute vượt quá dung lượng cho phép',
            ],
            [
                'txtMangaName' => 'Tên truyện',
                'txtMangaAnotherName' => 'Tên khác',
                'txtMangaAuthor' => 'Tên tác giả',
                'txtMangaDescribe' => 'Miêu tả',
                'slMangaStatus' => 'Trạng thái',
                'slMangaCategories' => 'Nhóm truyện',
                'txtMangaSlug' => 'Slug',
                'imgCover' => 'Hình bìa',
            ]
        );
        // $categories = implode(',', $request->slMangaCategories);
        if (!empty($request->imgCover)) {
            $store = $request->file('imgCover')->store('cover', 'public');
            if ($store) {
                $imgName = basename($store);
            } else {
                $imgName = 'default.jpg';
            }
        }
        $manga->name = $request->txtMangaName;
        $manga->another_name = $request->txtMangaAnotherName;
        $manga->author  = $request->txtMangaAuthor;
        $manga->describe = $request->txtMangaDescribe;
        $manga->active = $request->slMangaStatus;
        $manga->slug = $request->txtMangaSlug;
        if (isset($imgName)) {
            $manga->image_cover = $imgName;
        }
        $manga->save();
        $manga->categories()->sync($request->slMangaCategories);

        if ($manga->id) {
            return redirect()->route('admin.manga.list')->with('msg', 'sửa thành công');
        } else {
            return redirect()->route('admin.manga.list')->with('msg', 'Đã xảy ra lỗi');
        }
    }
    public function delete(Manga $manga)
    {
        $this->authorize('delete', $manga);

        if ($manga) {
            // dd($files = Storage::allFiles('public/cover'));
            // dd($manga->image_cover);
            if (Storage::exists('public/cover/' . $manga->image_cover) && $manga->image_cover != 'default.jpg') {
                Storage::delete('public/cover/' . $manga->image_cover);
            }
            if ($manga->delete()) {
                return redirect()->route('admin.manga.list')->with('msg', 'xóa thành công');
            }
        }
        return redirect()->route('admin.manga.list')->with('msg', 'xóa thất bại');
    }
    public function searchView(Request $request)
    {
        $data = Manga::where('active', 2)->whereHas('chapters', function ($query) {
            $query->where('active', 2);
        })->orderBy('updated_at', 'desc')->get();

        $condition = [];
        if ($request->keyword) {
            $keyword = trim(preg_replace('/[^A-Za-z0-9\s\ÀÁÂÃÈÉÊÌÍÒÓÔÕÙÚĂĐĨŨƠàáâãèéêìíòóôõùúăđĩũơƯĂẠẢẤẦẨẪẬẮẰẲẴặẸẺẼỀỀỂưăạảấầẩẫậắằẳẵặẹẻẽềềểỄỆỈỊỌỎỐỒỔỖỘỚỜỞỠỢỤỦỨỪễệỉịọỏốồổỗộớờởỡợụủứừỬỮỰỲỴÝỶỸửữựỳỵỷỹ]/', '', $request->keyword));
            $data = $data->filter(function ($item) use ($keyword) {
                if (str_contains(strtolower($item->name), strtolower($keyword)) || str_contains(strtolower($item->another_name), strtolower($keyword))) {
                    return $item;
                }
            });
        }
        if ($request->category) {
            $data = $data->filter(function ($item) use ($request) {
                if ($item->categories->contains('id', $request->category)) {
                    return $item;
                }
            });
        }
        if ($request->is_finished) {
            $data = $data->filter(function ($item) use ($request) {
                if ($item->is_finished == $request->is_finished) {
                    return $item;
                }
            });
        }
        if ($request->top) {
            if ($request->top == 'day') {
                $data = $data->sortByDesc(function ($item) {
                    return count($item->views->filter(function ($item) {
                        return $item->created_at->day == now()->day;
                    }));
                });
            }
            if ($request->top == 'week') {
                $data = $data->sortByDesc(function ($item) {
                    return count($item->views->filter(function ($item) {
                        return $item->created_at->week == now()->week;
                    }));
                });
            }

            if ($request->top == 'month') {
                $data = $data->sortByDesc(function ($item) {
                    return count($item->views->filter(function ($item) {
                        return $item->created_at->month == now()->month;
                    }));
                });
                // foreach ($data as $item) {
                //     echo $item->id . '-';
                //     echo count($item->views->filter(function ($item) {
                //         return $item->created_at->month == now()->month;
                //     })) . '</br>';
                // }
                // dd($data);
            }
        }
        if ($request->new) {
            $data = $data->sortByDesc('created_at');
        }
        if ($request->dateSort) {
            if ($request->dateSort == 'asc') {
                $data = $data->sortBy('updated_at');
            }
            if ($request->dateSort == 'desc') {
                $data = $data->sortByDesc('updated_at');
            }
        }

        if ($request->favorite) {
            $data = $data->sortByDesc(function ($item) {
                return count($item->likes);
            });
        }

        $data = paginate($data, 10);
        $title = 'Manga';
        return view('frontend.search', compact(
            'title',
            'data'
        ));
    }
    public function updateBookmark(Manga $manga)
    {
        if (Auth::user()) {
            $bookMark = Bookmark::where([
                ['manga_id', $manga->id],
                ['user_id', Auth::id()]
            ])->first();
            if ($bookMark) {
                $bookMark->delete();
                echo '1';
            } else {
                $bookMark = new Bookmark;
                $bookMark->user_id = Auth::id();
                $bookMark->manga_id = $manga->id;
                $bookMark->save();
                echo '2';
            }
        } else {
            echo '0';
        }
        // return view('errors.404');
    }
    public function updateLike(Manga $manga, Request $request)
    {
        $totalLike = $manga->likes;
        if (Auth::user()) {
            if (!$totalLike->contains('user_id', Auth::user()->id)) {
                $like = new Like;
                $like->user_id = Auth::user()->id;
                $like->manga_id = $manga->id;
                $like->save();
            } else {
                echo '0';
            }
            //else bạn đã thích truyện rồi
        } else {
            if ($request->session()->has('like')) {
                $value = $request->session()->get('like');
                $check = Carbon::parse($value['time'])->diffInMinutes();
                if ($value['id'] != $manga->id) {
                    $like = new Like;
                    $like->manga_id = $manga->id;
                    $like->save();
                    $request->session()->put('like', ['id' => $manga->id, 'time' => now()]);
                } else {
                    if ($check > 10) {
                        $like = new Like;
                        $like->manga_id = $manga->id;
                        $like->save();
                        $request->session()->put('like', ['id' => $manga->id, 'time' => now()]);
                    } else {
                        echo '0';
                    }
                }
            } else {
                $like = new Like;
                $like->manga_id = $manga->id;
                $like->save();
                $request->session()->put('like', ['id' => $manga->id, 'time' => now()]);
            }
        }
    }
    public function getBookMark()
    {
        $title = 'truyện đang theo dõi';

        $data = Auth::user()->bookmarks;
        // dd($data[0]->manga->chapters->last()->name);
        return view('frontend.bookmarks', compact('title', 'data'));
    }
    public function removeBookmark(Bookmark $bookmark)
    {
        if ($bookmark) {
            $bookmark->delete();
        }
        return redirect()->route('fe.getBookmark');
    }
    public function makeComment(Request $request)
    {
        $manga_id = $request->manga_id;
        $content = $request->content;
        $parent_id = $request->parent_id;
        $user_id = Auth::user()->id;
        if ($parent_id == 0) {
            $parent_id = NULL;
        }
        $comment = new Comment;
        $comment->manga_id = $manga_id;
        $comment->content = $content;
        $comment->user_id = $user_id;
        $comment->parent_comment_id = $parent_id;
        $comment->save();
        echo $comment->id;
    }
    public function removeComment(Request $request, Comment $comment)
    {
        // xử lý xóa comment cha và con và confirm trước khi xóa
        if ($comment) {
            if ($comment->user_id == Auth::user()->id) {
                $comment->delete();
                echo '1';
                return;
            } else {
                echo '0';
            }
        } else {
            echo '0';
        }
    }
}
