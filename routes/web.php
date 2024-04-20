<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\MangaController;
use App\Http\Controllers\ChapterController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// Route::get('/', function () {
//     return view('welcome');
// });
// Route::get('/', function () {
//     return view('frontend.home');
// })->name('fe.home');
Auth::routes([
    // 'register' => true,
    'verify' => true,
    // 'reset' => trie
]);
// Route::get('/test123/{id}', function ($id) {
//     echo $id;
// })->where(['id' => '[0-9]+',]);
Route::group(['prefix' => 'laravel-filemanager', 'middleware' => ['web', 'auth']], function () {
    \UniSharp\LaravelFilemanager\Lfm::routes();
});
Route::prefix('/')->name('fe.')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/manga/{manga   }', [HomeController::class, 'detailManga'])->name('detailManga');
    Route::get('chapter/{chapter}',  [ChapterController::class, 'readChapter'])->name('readChapter');
    Route::prefix('/search')->name('search.')->group(function () {
        Route::get('/',  [MangaController::class, 'searchView'])->name('searchView');
    });
    Route::get('/like/{manga}', [MangaController::class, 'updateLike'])->name('updateLike');
    Route::prefix('bookmark')->middleware('auth')->group(function () {
        Route::get('/{manga}', [MangaController::class, 'updateBookmark'])->name('updateBookmark');
        Route::get('/', [MangaController::class, 'getBookmark'])->name('getBookmark');
        Route::get('/remove/{bookmark}', [MangaController::class, 'removeBookmark'])->name('removeBookmark');
    });
    Route::prefix('/comment')->group(function () {
        Route::post('/', [MangaController::class, 'makeComment'])->name('makeComment')->middleware('auth');
        Route::get('remove/{comment}', [MangaController::class, 'removeComment'])->name('removeComment')->middleware('auth');
    });
});
Route::prefix('admin')->name('admin.')->middleware(['auth', 'verified'])->group(function () {
    Route::prefix('category')->name('category.')->group(function () {
        Route::get('/', [CategoryController::class, 'index'])->name('list')->can('category.view');
        Route::get('/add', [CategoryController::class, 'getCreate'])->name('getAdd')->can('category.add');
        Route::post('/add', [CategoryController::class, 'postCreate'])->name('postAdd')->can('category.add');
        Route::get('/edit/{category}', [CategoryController::class, 'getEdit'])->name('getEdit')->can('category.edit');
        Route::post('/edit/{category}', [CategoryController::class, 'postEdit'])->name('postEdit')->can('category.edit');
        Route::get('/delete/{category}', [CategoryController::class, 'delete'])->name('delete')->can('category.delete');
    });
    Route::prefix('manga')->name('manga.')->group(function () {
        Route::get('/', [MangaController::class, 'index'])->name('list')->can('manga.view');
        Route::get('/add', [MangaController::class, 'getCreate'])->name('getAdd')->can('manga.add');
        Route::post('/add', [MangaController::class, 'postCreate'])->name('postAdd')->can('manga.add');
        Route::get('/edit/{manga}', [MangaController::class, 'getEdit'])->name('getEdit')->can('manga.edit');
        Route::post('/edit/{manga}', [MangaController::class, 'postEdit'])->name('postEdit')->can('manga.edit');
        Route::get('/delete/{manga}', [MangaController::class, 'delete'])->name('delete')->can('manga.delete');
    });
    Route::prefix('chapter')->name('chapter.')->group(function () {
        Route::get('/{manga}', [ChapterController::class, 'index'])->name('list')->can('chapter.view');
        Route::get('/add/{manga}', [ChapterController::class, 'getAdd'])->name('getAdd')->can('chapter.add');
        Route::post('/add/{manga}', [ChapterController::class, 'postAdd'])->name('postAdd')->can('chapter.add');
        Route::get('/edit/{chapter}', [ChapterController::class, 'getEdit'])->name('getEdit')->can('chapter.edit');
        Route::post('/edit/{chapter}', [ChapterController::class, 'postEdit'])->name('postEdit')->can('chapter.edit');
        Route::get('/delete/{chapter}', [ChapterController::class, 'delete'])->name('delete')->can('chapter.delete');
        //ajax handle
        Route::post('getTemp', [ChapterController::class, 'getTempChapter'])->name('getTempChapter');
    });
    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('list')->can('user.view');
        Route::get('/add', [UserController::class, 'getAdd'])->name('getAdd')->can('user.add');
        Route::post('/add', [UserController::class, 'postAdd'])->name('postAdd')->can('user.add');
        Route::get('/delete/{user}', [UserController::class, 'delete'])->name('delete')->can('user.delete');
        Route::get('/edit/{user}', [UserController::class, 'getEdit'])->name('getEdit')->can('update', 'user'); // phân quyền policy
        Route::post('/edit/{user}', [UserController::class, 'postEdit'])->name('postEdit')->can('update', 'user'); // phân quyền policy
        Route::get('/change-password/{user}', [UserController::class, 'getChangePassword'])->name('getChangePassword');
        Route::post('/change-password/{user}', [UserController::class, 'postChangePassword'])->name('postChangePassword');
    });
    Route::prefix('group')->name('group.')->group(function () {
        Route::get('/', [GroupController::class, 'index'])->name('list')->can('group.view');;
        Route::get('/add', [GroupController::class, 'getAdd'])->name('getAdd')->can('group.add');
        Route::post('/add', [GroupController::class, 'postAdd'])->name('postAdd')->can('group.add');
        Route::get('/edit/{group}', [GroupController::class, 'getEdit'])->name('getEdit')->can('group.edit');
        Route::post('/edit/{group}', [GroupController::class, 'postEdit'])->name('postEdit')->can('group.edit');
        Route::get('/delete/{group}', [GroupController::class, 'delete'])->name('delete')->can('group.delete');
    });
    Route::get('/', [DashboardController::class, 'index'])->name('home');
});
//! email verify
Route::get('/email/verify', function () {
    return view('auth.verify');
})->middleware('auth')->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/');
})->middleware(['auth', 'signed'])->name('verification.verify');
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.resend');
// Route::group([
//     'name' => 'admin.',
//     'prefix' => 'admin',
//     'middleware' => 'auth',
// ], function () {
//     Route::get('/', function () {
//         return view('backend.home');
//     })->name('home');
// });