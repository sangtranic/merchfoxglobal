<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\ProductCategoriesController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\ApiController;
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

Route::get('/', [HomeController::class, 'index'])->name('home');
//account
Route::get('/login', [Controllers\AccountController::class, 'login'] )->name('login');
Route::post('/do-login', [Controllers\AccountController::class, 'doLogin'])->name('doLogin');
Route::get('/forgot-password', [Controllers\AccountController::class, 'forgotPassword'] )->name('forgotPassword');
Route::post('/forgot-password-sent', [Controllers\AccountController::class, 'sendResetLinkEmail'] )->name('sendResetLinkEmail');
Route::get('/logout',  [Controllers\AccountController::class, 'logout'])->name('logout');

//user
Route::resource('users', UsersController::class );
Route::get('users', [Controllers\UsersController::class, 'index'] )->name('users.index')->middleware('role:admin');
Route::get('users/create', [Controllers\UsersController::class, 'create'] )->name('users.create')->middleware('role:admin');
Route::get('users/store', [Controllers\UsersController::class, 'store'] )->name('users.store')->middleware('role:admin');
Route::get('users/destroy', [Controllers\UsersController::class, 'store'] )->name('users.destroy')->middleware('role:admin');
Route::get('users/{id}/changepassword', [UsersController::class, 'changepassword'])->name('users.changepassword');
Route::put('users/updatepassword', [UsersController::class, 'updatepassword'])->name('users.updatepassword');
//role
Route::resource('roles', Controllers\RolesController::class );
//VPS
Route::resource('vps', Controllers\VpsController::class );
//Seller
Route::resource('seller', Controllers\SellerController::class );
//
Route::get('/page/{page?}', [HomeController::class, 'Page']);
//image
Route::controller(ImageController::class)->group(function(){
    Route::get('image-upload', 'index');
    Route::post('image-upload', 'store')->name('image.store');
});
//products
Route::resource('product-cates', ProductCategoriesController::class );
Route::resource('products', Controllers\ProductController::class) ;
Route::get('products-search', [Controllers\ProductController::class, 'search'])->name('products.search');
//orders
Route::resource('orders', Controllers\OrdersController::class );
Route::get('orderForm', [Controllers\OrdersController::class, 'editForm'])->name("orders.editForm");

Route::get('orders/{id}', [Controllers\OrdersController::class, 'detail'])->name("orders.detail");
Route::get('orders-search', [Controllers\OrdersController::class, 'search'])->name('orders.search');


Route::get('/export-to-csv', [OrdersController::class, 'exportToCsv'])->name('export-to-csv');
Route::post('/import-csv', [OrdersController::class, 'importCsv'])->name('import-csv');

//api
Route::get('api-orders-search', 'ApiController@ordersSearch')->name("api-orders-search");
Route::get('api-vpses-search', [ApiController::class,'vpsSearch'])->name("api-vpses-search");
Route::get('api-products-search', [ApiController::class,'productsSearch'])->name("api-products-search");
Route::get('api-product-detail', [ApiController::class,'productDetail'])->name("api-product-detail");
Route::get('api-product-category-detail', [ApiController::class,'productCategoryDetail'])->name("api-product-category-detail");
