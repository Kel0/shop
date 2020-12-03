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
    return redirect("home");
});

Auth::routes();

Route::get('/home', 'PostsController@index')->name('phorum');

Route::get('/shop', 'ShopController@index')->name('shop');
Route::get('/phorum/posts.get', 'PostsController@posts_get')->name('posts_get');
Route::get('/shop/products.get', 'ShopController@get_products')->name("get_products");
Route::get('/profile', 'ProfileController@index')->name('profile');
Route::get('/admin', 'ShopController@index_admin')->name('admin')->middleware("is_admin");

Route::post('/phorum/post.create', 'PostsController@post_create')->name('post_create');
Route::post('/phorum/post.like_dislike.update', 'PostsController@post_like_dislike_update')->name('post_like_dislike_update'); 
Route::post('/phorum/post.comment.create', 'PostsController@post_comment_create')->name('post_comment_create');
Route::post('/phorum/post.comment.like_dislike.update', 'PostsController@post_comments_like_dislike_update')->name('post_comments_like_dislike_update'); 
Route::post('/shop/product.purchase', 'ShopController@purchase_product')->name("purchase_item");
Route::post('/image-upload', 'ProfileController@image_upload_post')->name('image.upload.post');
Route::post('/product-create', 'ShopController@create_product')->name("product.create");
Route::post('/product-delete', 'ShopController@delete_product')->name('delete_product');
