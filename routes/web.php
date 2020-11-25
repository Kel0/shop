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

Route::get('/phorum', 'PostsController@index')->name('phorum');
Route::get('/phorum/posts.get', 'PostsController@posts_get')->name('posts_get');

Route::post('/phorum/post.create', 'PostsController@post_create')->name('post_create');
Route::post('/phorum/post.comment.create', 'PostsController@post_comment_create')->name('post_comment_create');
