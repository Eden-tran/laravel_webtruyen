<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\View;
use App\Models\Manga;
use App\Models\Chapter;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ChapterController extends Controller
{
    public function index(Manga $manga)
    {
        $this->authorize('viewAny', [Chapter::class, $manga]);

        $scope = getScope('Chapter');
        $mangaId = $manga->id;
        $perPage = 10;
        if ($scope == 1) {
            $chapters = Chapter::where('manga_id', $mangaId)->paginate($perPage);
        } else {
            $chapters = Chapter::where([
                ['manga_id', $mangaId],
                ['user_id', Auth::user()->id],
            ])->paginate($perPage);
            // $chapters = Chapter::paginate($perPage);
        }

        $title = 'Danh sách chương';
        return view('backend.chapter.list', compact('title', 'chapters', 'manga'));
    }
    public function getAdd($mangaId)
    {
        $this->authorize('create', Chapter::class);
        if ($mangaId) {
            $title = 'Thêm chương mới';
            return view('backend.chapter.addForm', compact('title', 'mangaId'));
        }
        // return 404 nếu không có mangaId
        // return view('backend.manga.addForm', compact('title'));
    }
    public function getTempChapter(Request $request) // ajax method
    {
        $chapterId = $request->chapterId;
        $validated = $request->validate;
        $res = [];
        $method = $request->method;
        // $request = Request::capture();
        //! đổ dữ liệu hình khi edit lần đầu. Lấy trực tiếp từ public/chapter
        //! validate fail đổ dữ liệu lại từ temp.
        //! Khi upload sẽ clear public/chapter reupload từ temp

        if ($chapterId) {
            if ($method == 'edit') {
                if (Storage::exists("public/chapter/$chapterId")) {
                    $files = Storage::allFiles("public/chapter/$chapterId");
                } elseif (Storage::exists("public/tempChapter/$chapterId")) {
                    $files = Storage::allFiles("public/tempChapter/$chapterId");
                }

                if ($validated == 'true') {
                    $files = Storage::allFiles("public/tempChapter/$chapterId");
                }
                // else return false
            } else {
                if (Storage::exists("public/tempChapter/$chapterId")) {
                    $files = Storage::allFiles("public/tempChapter/$chapterId");
                }
            }
            if ($files) {
                foreach ($files as $file) {
                    $res[] = str_replace('public', '/storage',  $file);;
                }
            }
            header('Content-type: application/json');
            echo json_encode($res);
        }
    }
    public function postAdd($mangaId, Request $request)
    {
        //! clean all the file before upload
        // * Store chapter into temp with folder name = Id of chapter
        $image = $request->file('file');
        if ($image) {
            if ($request->chapterId) {
                $chapterId = $request->chapterId;
            } else {
                $chapter = new Chapter();
                $chapter->name = '';
                $chapter->user_id = $request->user()->id;
                $chapter->slug = '';
                $chapter->active = 1;
                $chapter->status = 1;
                $chapter->manga_id = $mangaId;
                $chapter->save();
                $chapterId = $chapter->id;
            }
            if (Storage::exists("public/tempChapter/$chapterId")) {
                Storage::deleteDirectory("public/tempChapter/$chapterId");
            }
            foreach ($image as $key => $value) {
                $imageName = $key + 1 . '.' . $value->extension();
                $store = $value->storeAs(
                    //? lastId +1
                    "tempChapter/$chapterId",
                    $imageName,
                    'public'
                );
            }
            echo $chapterId;
        }
        //!example for get last modified time of image
        // $image_path = 'storage/cover/default.jpg';
        // // Convert the relative path to an absolute file path on the server
        // $public_dir = public_path();
        // $file_path = $public_dir . '/' . $image_path;
        // // Get the last modified time of the file
        // $last_modified_timestamp = filemtime($file_path);
        // $last_modified_date = date('Y-m-d H:i:s', $last_modified_timestamp);
        // dd($last_modified_date);
        //!example for get last modified time of image
        if ($request->chapterId && !$image) {
            if ($request->chapterId == null) {
                return redirect()->route('admin.chapter.addForm', $mangaId)->with(
                    'msg',
                    'Vui lòng kiểm tra lại dữ liệu'
                );
            } else {
                $request->validate(
                    [
                        'txtChapterName' => 'required|max:250',
                        'slChapterActive' => 'required',
                        'txtChapterSlug' => 'required|max:250',
                    ],
                    [
                        'txtChapterName.required' => ':attribute không được bỏ trống',
                        'txtChapterName.max' => ':attribute tối đa :max kí tự',
                        'slChapterActive.required' => ':attribute không được bỏ trống',
                        'txtChapterSlug.required' => ':attribute không được bỏ trống',
                        'txtChapterSlug.max' => ':attribute tối đa :max kí tự',
                    ],
                    [
                        'txtChapterName' => 'Tên chương truyện',
                        'slChapterActive' => 'Trạng thái chương truyện',
                        'txtChapterSlug' => 'Slug',
                    ]
                );
            }
            $chapter = Chapter::find($request->chapterId);
            $chapter->name = $request->txtChapterName;
            $chapter->active = $request->slChapterActive;
            $chapter->status = 2;
            $chapter->user_id = $request->user()->id;
            $chapter->slug = $request->txtChapterSlug;
            $chapter->save();
            if ($chapter->id) {
                //! xử lý của page model
                // $absolutePath = Storage::path("public/tempChapter/$chapter->id");
                $files = Storage::allFiles("public/tempChapter/$chapter->id");
                foreach ($files as $file) {
                    $page = new Page();
                    $page->chapter_id = $chapter->id;
                    $page->name = basename($file);
                    $page->save();
                    if ($page->id) {
                        Storage::move($file, "public/chapter/$chapter->id/" . basename($file));
                    }
                }
                $isDirectoryEmpty = count(Storage::allFiles("public/tempChapter/$chapter->id")) == 0;
                if ($isDirectoryEmpty) {
                    Storage::deleteDirectory("public/tempChapter/$chapter->id");
                }
                return redirect()->route('admin.chapter.list', $mangaId)->with('msg', 'thêm chương mới thành công');
            } else {
                return redirect()->route('admin.chapter.list', $mangaId)->with('msg', 'Đã xảy ra lỗi');
            }
        }
        // // // return 404;
    }
    public function getEdit(Chapter $chapter)
    {
        $title = 'Edit chapter';
        return view('backend.chapter.editForm', compact('title', 'chapter'));
    }
    public function postEdit(Request $request, Chapter $chapter)
    {
        //! clean all the file before upload
        // * Store chapter into temp with folder name = Id of chapter
        $image = $request->file('file');
        if ($image && $request->chapterId) {
            $chapterId = $request->chapterId;
            if (Storage::exists("public/tempChapter/$chapterId")) {
                Storage::deleteDirectory("public/tempChapter/$chapterId");
            }
            foreach ($image as $key => $value) {
                $imageName = $key + 1 . '.' . $value->extension();
                $store = $value->storeAs(
                    //? lastId +1
                    "tempChapter/$chapterId",
                    $imageName,
                    'public'
                );
            }
            echo $chapterId;
        }
        //!example for get last modified time of image
        // $image_path = 'storage/cover/default.jpg';
        // // Convert the relative path to an absolute file path on the server
        // $public_dir = public_path();
        // $file_path = $public_dir . '/' . $image_path;
        // // Get the last modified time of the file
        // $last_modified_timestamp = filemtime($file_path);
        // $last_modified_date = date('Y-m-d H:i:s', $last_modified_timestamp);
        // dd($last_modified_date);
        //!example for get last modified time of image
        if ($request->chapterId && !$image) {
            if ($request->chapterId == null) {
                return redirect()->route('admin.chapter.addForm', $$chapter->manga_id)->with(
                    'msg',
                    'Vui lòng kiểm tra lại dữ liệu'
                );
            } else {
                try {
                    // validate the form data
                    $request->validate([
                        'txtChapterName' => "required|max:250",
                        'slChapterActive' => 'required',
                        'txtChapterSlug' => "required|max:250",
                    ], [
                        'txtChapterName.required' => ':attribute không được bỏ trống',
                        'txtChapterName.max' => ':attribute tối đa :max kí tự',
                        'slChapterActive.required' => ':attribute không được bỏ trống',
                        'txtChapterSlug.required' => ':attribute không được bỏ trống',
                        'txtChapterSlug.max' => ':attribute tối đa :max kí tự',
                    ], [
                        'txtChapterName' => 'Tên chương truyện',
                        'slChapterActive' => 'Trạng thái chương truyện',
                        'txtChapterSlug' => 'Slug',
                    ]);
                    $chapter = Chapter::find($request->chapterId);
                    $chapter->name = $request->txtChapterName;
                    $chapter->active = $request->slChapterActive;
                    $chapter->status = 2;
                    $chapter->slug = $request->txtChapterSlug;
                    $chapter->save();
                    if ($chapter->id) {
                        //! xử lý của page model
                        if ($page = Page::whereIn('chapter_id', [$chapter->id])->delete()) {
                            Storage::deleteDirectory("public/chapter/$chapter->id");
                        };

                        // $absolutePath = Storage::path("public/tempChapter/$chapter->id");
                        $files = Storage::allFiles("public/tempChapter/$chapter->id");
                        foreach ($files as $file) {
                            $page = new Page();
                            $page->chapter_id = $chapter->id;
                            $page->name = basename($file);
                            $page->save();
                            if ($page->id) {
                                Storage::move($file, "public/chapter/$chapter->id/" . basename($file), 'force');
                            }
                        }
                        $isDirectoryEmpty = count(Storage::allFiles("public/tempChapter/$chapter->id")) == 0;
                        if ($isDirectoryEmpty) {
                            Storage::deleteDirectory("public/tempChapter/$chapter->id");
                        }
                        return redirect()->route('admin.chapter.list', $chapter->manga_id)->with('msg', 'sửa chương thành công');
                    } else {
                        return redirect()->route('admin.chapter.list', $chapter->manga_id)->with('msg', 'Đã xảy ra lỗi');
                    }
                } catch (\Illuminate\Validation\ValidationException $e) {
                    // if validation fails, set the value of "validated" to true
                    return redirect()->back()->withInput(['validated' => 'true'])->withErrors($e->validator);
                }
            }
        }
    }
    public function delete(Chapter $chapter)
    {
        $mangaId = $chapter->manga_id;
        if ($chapter->exists()) {
            if ($chapter->delete()) {
                Storage::deleteDirectory("public/chapter/$chapter->id");
                return redirect()->route('admin.chapter.list', $mangaId)->with('msg', 'Xóa thành công');
            }
        }
        return redirect()->route('admin.chapter.list', $mangaId)->with('msg', 'Đã có lỗi xảy ra');
    }
    public function readChapter(Chapter $chapter, Request $request)
    {
        if ($chapter->active == 1 || $chapter->manga->active == 1) {
            return abort(404);
        }
        $title = 'Chapter';
        $pages = Page::where('chapter_id', '=', $chapter->id)->get();
        $comments = Comment::tree($chapter->manga_id)->values()->sortByDesc('created_at');

        $allChapter = $chapter->manga->chapters;
        $allChapter = $allChapter->filter(function ($item) {
            return $item->active == 2;
        })->values();

        $previousChap = Chapter::where('id', '<', $chapter->id)->max('id');
        // get next user id
        $nextChap = Chapter::where('id', '>', $chapter->id)->min('id');
        if ($request->session()->has('view')) {
            $value = $request->session()->get('view');
            if ($value['id'] != $chapter->id) {
                $view = new View();
                if (Auth::user()) {
                    $view->user_id = Auth::user()->id;
                }
                $view->chapter_id = $chapter->id;
                $view->save();
                $request->session()->put('view', ['id' => $chapter->id]);
            }
        } else {
            $view = new View();
            if (Auth::user()) {
                $view->user_id = Auth::user()->id;
            }
            $view->chapter_id = $chapter->id;
            $view->save();
            $request->session()->put('view', ['id' => $chapter->id]);
        }
        return view('frontend.chapter', compact(
            'title',
            'pages',
            'comments',
            'chapter',
            'allChapter',
            'previousChap',
            'nextChap'
        ));
    }
}
