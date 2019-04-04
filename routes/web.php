<?php

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
    return view('welcome');
});

Auth::routes();
Route::get('/home', 'HomeController@index')->name('home')->middleware('roleauth:admin|editor');


Route::namespace('Admin')->prefix('admin')->group(function () {
	Route::get('/', [
        'uses' => 'IndexController@index',
        'as' => 'admin.index.index'
    ]);
	Route::get('/post/search', 'PostIndexController@search')->name('post.search');
	Route::resource('post', 'PostIndexController');
    Route::resource('cat', 'CategoryController');
    Route::get('/cat/search', 'CategoryController@search')->name('cat.search');
});


