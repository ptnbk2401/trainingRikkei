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

Route::get('/home', 'HomeController@index')->name('home');
Route::namespace('Admin')->middleware('auth')->group(function () {
	// Route::post('/post/edit/{id}', 'PostIndexController@update')->name('post.pedit');
	// Route::get('/post/del/{id}', 'PostIndexController@destroy')->name('post.del');
	Route::get('/post/search', 'PostIndexController@search')->name('post.search');
	Route::resource('post', 'PostIndexController');
});