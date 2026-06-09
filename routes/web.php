<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

use App\Models\Book;
use App\Models\Category;
use App\Models\Bookmark;
use App\Models\Comment;
use App\Models\ReadHistory;
use App\Models\Setting;
use App\Models\Visitor;

use Barryvdh\DomPDF\Facade\Pdf;

use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ReadHistoryController;
use App\Http\Controllers\BookReadController;
use App\Http\Controllers\BookPdfController;
use App\Http\Controllers\BookCategoryController;
use App\Http\Controllers\LandingController;

use App\Http\Controllers\Admin\BookController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\CommentController as AdminCommentController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\ReadHistoryController as AdminReadHistoryController;



Route::get('/', [LandingController::class, 'index']);

Route::get('/category/{id}', [BookCategoryController::class, 'show'])
    ->name('books.category');


Route::get('/dashboard', function () {

    return redirect()->route('admin.dashboard');

    })->middleware(['auth', 'role:admin'])->name('dashboard');

/* ADMIN LOGIN */
Route::get('/admin-login', function () {

    return view('auth.login');

})->name('admin.login');



/* ADMIN ROUTES */

Route::prefix('admin')
    ->middleware(['auth', 'role:admin'])
    ->name('admin.')
    ->group(function () {

Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');


Route::resource('books', BookController::class);


Route::resource('categories', CategoryController::class);


Route::resource('users', UserController::class)
            ->except(['show', 'create', 'store']);


Route::get('/read-histories', [AdminReadHistoryController::class, 'index'])
    ->name('read_histories');


Route::get('/profile', [ProfileController::class, 'index'])
    ->name('profile');

Route::post('/profile/update', [ProfileController::class, 'update'])
    ->name('profile.update');


Route::get('/reports', [ReportController::class, 'index'])
            ->name('reports');

Route::get('/reports/books/pdf', [ReportController::class, 'booksPdf'])
            ->name('reports.books.pdf');


Route::get('/reports/categories/pdf', [ReportController::class, 'categoriesPdf'])
            ->name('reports.categories.pdf');


Route::get('/reports/visitors/pdf', [ReportController::class, 'visitorsPdf'])
            ->name('reports.visitors.pdf');
        

Route::get('/comments', [AdminCommentController::class, 'index'])
    ->name('comments');

Route::delete('/comments/{id}', [AdminCommentController::class, 'destroy'])
    ->name('comments.destroy');


Route::get('/announcements', [AnnouncementController::class, 'index'])
    ->name('announcements');

Route::get('/announcements/create', [AnnouncementController::class, 'create'])
    ->name('announcements.create');

Route::post('/announcements', [AnnouncementController::class, 'store'])
    ->name('announcements.store');

Route::delete('/announcements/{id}', [AnnouncementController::class, 'destroy'])
    ->name('announcements.destroy');


Route::get('/settings', [SettingController::class, 'index'])
    ->name('settings');

Route::post('/settings', [SettingController::class, 'update'])
    ->name('settings.update');

Route::get('/settings', [SettingController::class, 'index'])
    ->name('settings');
    
Route::post('/settings', [SettingController::class, 'update'])
    ->name('settings.update');

    });


/*
|--------------------------------------------------------------------------
| READ BOOK
|--------------------------------------------------------------------------
*/

Route::get('/books/read/{id}', [BookReadController::class, 'read'])
    ->name('books.read');


/*
|--------------------------------------------------------------------------
| BOOKMARK
|--------------------------------------------------------------------------
*/

Route::post('/bookmark/{id}', [BookmarkController::class, 'store'])
    ->name('bookmark.store');

Route::post('/bookmark/remove/{id}', [BookmarkController::class, 'remove'])
    ->name('bookmark.remove');

Route::get('/my-bookmarks', [BookmarkController::class, 'list'])
    ->name('bookmark.list');


/*
|--------------------------------------------------------------------------
| COMMENTS
|--------------------------------------------------------------------------
*/

Route::get('/comment/{id}', [CommentController::class, 'redirect']);

Route::post('/comment/{id}', [CommentController::class, 'store'])
    ->name('comment.store');


/*
|--------------------------------------------------------------------------
| READ HISTORY
|--------------------------------------------------------------------------
*/

Route::get('/history', [ReadHistoryController::class, 'index'])
    ->name('history');


/*
|--------------------------------------------------------------------------
| PDF REPORTS
|--------------------------------------------------------------------------
*/
Route::get('/books/pdf/{id}', [BookPdfController::class, 'show'])
    ->name('books.pdf');


/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';
