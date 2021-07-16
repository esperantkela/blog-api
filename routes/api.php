<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;


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



//public routes
Route::post('/register', 'AuthController@register')->name('register');
Route::post('/login','AuthController@login')->name('login');

//protected routes

Route::group(['middleware' =>['auth:sanctum']], function () {
    Route::get('/user', 'AuthController@user')->name('user');
    Route::post('/logout', 'AuthController@logout')->name('logout');

    Route::resource('posts', 'PostController');

    //comments routes
    Route::get('posts/{id}/comments', 'CommentController@index')->name('indexcomment');
    Route::post('posts/{id}/comments', 'CommentController@store')->name('storecomment');
    Route::put('/comments/{id}', 'CommentController@update')->name('updatecomment');
    Route::delete('/comments/{id}', 'CommentController@destroy')->name('deletecomment');

    //likes routes


    Route::get('posts/{id}/likes', 'LikeController@likeOrUnlike')->name('likeordislike');

});
