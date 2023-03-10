<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\ProductCategoriesController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\RestApiController;
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
Route::post('users/store', [Controllers\UsersController::class, 'store'] )->name('users.store')->middleware('role:admin');
Route::get('users/{id}/changepassword', [UsersController::class, 'changepassword'])->name('users.changepassword');
Route::put('users/updatepassword', [UsersController::class, 'updatepassword'])->name('users.updatepassword');
Route::get('/getUserByVpsId', [UsersController::class, 'getByVpsId'])->name('getUserByVpsId');
//role
Route::resource('roles', Controllers\RolesController::class );
//VPS
Route::resource('vps', Controllers\VpsController::class );
//Seller
Route::resource('seller', Controllers\SellerController::class );
Route::get('/getListSellerByUserId', [Controllers\SellerController::class, 'getByUserId'])->name('getListSellerByUserId');
Route::get('/getListSellerByVpsId', [Controllers\SellerController::class, 'getByVpsId'])->name('getListSellerByVpsId');
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
Route::get('api-order-row', [Controllers\OrdersController::class, 'orderRow'])->name('api-order-row');
Route::post('orders-index-post', [Controllers\OrdersController::class, 'indexPost'])->name('orders.indexPost');

Route::get('/export-csv', [OrdersController::class, 'exportCSV'])->name('export-csv');
Route::get('/export-orders', [OrdersController::class, 'exportOrders'])->name('export-orders');
Route::get('/export-up-ebay', [OrdersController::class, 'exportUpToEbay'])->name('export-up-ebay');
Route::post('/import-csv', [OrdersController::class, 'importCsv'])->name('import-csv');

//api
Route::get('api-orders-search', 'ApiController@ordersSearch')->name("api-orders-search");
Route::get('api-vpses-search', [ApiController::class,'vpsSearch'])->name("api-vpses-search");
Route::get('api-products-search', [ApiController::class,'productsSearch'])->name("api-products-search");
Route::get('api-product-detail', [ApiController::class,'productDetail'])->name("api-product-detail");
Route::get('api-product-category-detail', [ApiController::class,'productCategoryDetail'])->name("api-product-category-detail");
Route::get('api-orders-remove', [ApiController::class,'removeOrders'])->name("api-orders-remove");
Route::post('api/orders-import', [RestApiController::class,'orderImport'])->name("api-orders-import");
Route::post('api/orders-csv-import', [RestApiController::class,'orderCSVImport'])->name("api-orders-csv-import");
