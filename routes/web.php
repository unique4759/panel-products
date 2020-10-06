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

Route::get('/', function () {
    return view('main');
});

// Products
Route::get('/products', 'ProductController@adminPage')->name('products.adminPage');
Route::post('/products/get-data', 'ProductController@getData')->name('products.getData');
Route::post('/product/store', 'ProductController@store')->name('product.store');
Route::post('/product/update', 'ProductController@update')->name('product.update');
Route::post('/product/delete', 'ProductController@delete')->name('product.delete');

// Categories
Route::get('/categories', 'CategoryController@adminPage')->name('categories.adminPage');
Route::post('/categories/get-data', 'CategoryController@getData')->name('categories.getData');
Route::post('/category/store', 'CategoryController@store')->name('category.store');
Route::post('/category/update', 'CategoryController@update')->name('category.update');
Route::post('/category/delete', 'CategoryController@delete')->name('category.delete');
