<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Auth::routes();

# We can't without login
Route::group(['middleware' => 'auth'], function () {
    Route::get('/', [PostController::class, 'index'])->name('index');

    # POST ROUTES
    // Option 1 Manual
    Route::get('/post/create', [PostController::class, 'create'])->name('post.create');

    // Option 2 Route Group
    // Route::group(['prefix' => 'post', 'as' => 'post.'], function () {
    //     // prefix ~~ this will add the '/post' inside the URL
    //     // as ~~ this will assign a name for all the routes inside this group with 'post.[route_name]'
    //     Route::get('/create', [PostController::class, 'create'])->name('create');
    // });

    Route::post('/post/store', [PostController::class, 'store'])->name('post.store');
    Route::get('/{id}/show', [PostController::class, 'show'])->name('post.show');
    Route::get('/{id}/edit', [PostController::class, 'edit'])->name('post.edit');
    Route::patch('/{id}/update', [PostController::class, 'update'])->name('post.update');
    Route::delete('/{id}/destroy', [PostController::class, 'destroy'])->name('post.destroy');

    # COMMENT ROUTES
    Route::group(['prefix' => 'comment', 'as' => 'comment.'], function () {
        Route::post('/{post_id}/store', [CommentController::class, 'store'])->name('store');
        Route::delete('/{id}/destroy', [CommentController::class, 'destroy'])->name('destroy');
    });


});
