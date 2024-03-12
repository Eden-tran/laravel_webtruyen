<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Manga;
use App\Models\Bookmark;
use App\Models\Category;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $title = 'Web Truyện';
        // $manga = Manga::where('active', 2)->get();
        // $manga = Manga::whereHas('chapters', function ($query) {
        //     $query->where('active', 2);
        // })->orderBy('updated_at', 'desc')->get();
        $manga = Manga::where('active', 2)->whereHas('chapters', function ($query) {
            $query->where('active', 2);
        })->orderBy('updated_at', 'desc')->get();
        // $manga->unique()->sortBy('created_at');
        // $test = $manga[0]->chapters()->count();
        return view('frontend.home', compact('title', 'manga'));
    }
    public function detailManga(Manga $manga, Request $request)
    {
        if ($manga->active == 1) {
            return abort(404);
        }
        $title = $manga->name;
        // $comments = Comment::where('manga_id', $manga->id)->tree()->get()->toTree();
        $comments = Comment::tree($manga->id)->values()->sortByDesc('created_at');
        if (Auth::user()) {
            $bookmarks = Bookmark::where('user_id',)->get();
        }
        return view('frontend.detail', compact('title', 'manga', 'comments'));
    }
}
