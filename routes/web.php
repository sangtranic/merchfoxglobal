<?php

use Illuminate\Support\Facades\Route;

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
Route::get('/', [\App\Http\Controllers\HomeController::class, 'Index']);
Route::get('/home', function () {
    return view('home');
});
Route::get('/page/{page?}', [\App\Http\Controllers\HomeController::class, 'Page']);
Route::resource('posts', 'PostController');
