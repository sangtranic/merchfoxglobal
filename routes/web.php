<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers;

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

Route::get('/', [UserController::class, 'index']);
Route::resource('users', UsersController::class );
//Route::controller(UsersController::class)->group(function(){
//    Route::get('changepassword', 'changepassword')->name('users.changepassword');
//    Route::post('updatepassword', 'updatepassword')->name('users.updatepassword');
//});
Route::get('users/{id}/changepassword', [UsersController::class, 'changepassword'])->name('users.changepassword');
Route::put('users/updatepassword', [UsersController::class, 'updatepassword'])->name('users.updatepassword');
Route::resource('roles', Controllers\RolesController::class );
Route::get('/home', function () { return view('home');});
Route::get('/page/{page?}', [HomeController::class, 'Page']);
Route::controller(ImageController::class)->group(function(){
    Route::get('image-upload', 'index');
    Route::post('image-upload', 'store')->name('image.store');
});
