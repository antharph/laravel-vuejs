<?php

use Illuminate\Http\Request;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// Route::post('/register', function (Request $request) {
//     dd('xxx');
// });

Route::middleware(['auth:api'])->group(function () {

    Route::post('/logout', ['as' => 'user.logout', 'uses' => '\App\Http\Controllers\Api\LoginController@logout']);
    Route::post('/posts', ['as' => 'auth.post.create', 'uses' => '\App\Http\Controllers\Api\PostsController@create']);
    Route::patch('/posts/{slug}', ['as' => 'auth.post.updateBySlug', 'uses' => '\App\Http\Controllers\Api\PostsController@updateBySlug']);
    Route::delete('/posts/{slug}', ['as' => 'auth.post.deleteBySlug', 'uses' => '\App\Http\Controllers\Api\PostsController@deleteBySlug']);
    Route::post('/posts/{slug}/comments', ['as' => 'auth.post.createPostComment', 'uses' => '\App\Http\Controllers\Api\PostsController@createPostComment']);

    Route::patch('/posts/{slug}/comments/{id}', ['as' => 'auth.post.updatePostComment', 'uses' => '\App\Http\Controllers\Api\PostsController@updatePostComment']);
    Route::delete('/posts/{slug}/comments/{id}', ['as' => 'auth.post.deletePostComment', 'uses' => '\App\Http\Controllers\Api\PostsController@deletePostComment']);

});


Route::post('/register', ['as' => 'user.register', 'uses' => '\App\Http\Controllers\Api\RegisterController@create']);
Route::post('/login', ['as' => 'user.login', 'uses' => '\App\Http\Controllers\Api\LoginController@authenticate']);
Route::get('/posts/{slug}/comments', ['as' => 'guest.postsBySlug', 'uses' => '\App\Http\Controllers\Api\PostsController@comments']);
Route::get('/posts/{slug}', ['as' => 'guest.postsBySlug', 'uses' => '\App\Http\Controllers\Api\PostsController@getBySlug']);
Route::get('/posts', ['as' => 'guest.posts', 'uses' => '\App\Http\Controllers\Api\PostsController@index']);
