<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\{
	UserController, ArticleController,
	RegisterController, LoginController,
	LogoutController, ForgotController,
	ResetController, CommentController,
	CategoryController
};

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

Route::get('register', [RegisterController::class, 'index'])->name('register');
Route::get('login', [LoginController::class, 'index'])->name('login');
Route::get('logout', [LogoutController::class, 'logout'])->name('logout');
Route::get('forgot', [ForgotController::class, 'index'])->name('forgot');

Route::get('category/{category}', [CategoryController::class, 'show'])->name('category.show');

Route::get('user/edit', [UserController::class, 'edit'])->name('user.edit');

Route::get('user/password', [UserController::class, 'password'])->name('user.password');

Route::post('password', [UserController::class, 'updatePassword'])->name('update.password');

Route::post('user/store', [UserController::class, 'store'])->name('post.user');

Route::post('comment/{article}', [CommentController::class, 'store'])->name('post.comment');

Route::get('reset/{token}', [ResetController::class, 'index'])->name('reset');
Route::post('reset', [ResetController::class, 'reset'])->name('post.reset');

Route::post('register', [RegisterController::class, 'register'])->name('post.register');
Route::post('login', [LoginController::class, 'login'])->name('post.login');
Route::post('forgot', [ForgotController::class, 'store'])->name('post.forgot');

Route::get('profile/{user}', [UserController::class, 'profile'])->name('user.profile');

Route::delete('destroy/{user}', [UserController::class, 'destroy'])->name('user.destroy');

Route::resource('articles', ArticleController::class)->except('index');

Route::get('/', [ArticleController::class, 'index']);

// Route::get('structures', function(){
// 	$fruits = ['pommes', 'organge', 'mandarines', 'citrons'];
// 	$data = [
// 		'number'=>21,
// 		'fruits'=>$fruits,
// 	];
// 	return view('structures', $data);
// });

// Route::get('test', function(){
// 	return view('test')->withTitle('Laravel');
// });

// Route::get('test2', function(){
// 	return view('test2')->withTitle('PHP');
// });

