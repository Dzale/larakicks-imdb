<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| UserController Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all routes for UserController. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'users'], function () {
    Route::get('me', 'UserController@me')
        ->name('users.me');

    Route::post('me', 'UserController@update')
        ->name('users.update');

    Route::post('me/password', 'UserController@updatePassword')
        ->name('users.updatePassword');

    Route::get('favorite-movies', 'UserController@searchFavoriteMovies')
        ->name('users.searchFavoriteMovies');

    Route::post('favorite-movies/{movie}', 'UserController@attachFavoriteMovie')
        ->name('users.attachFavoriteMovie');

    Route::delete('favorite-movies/{movie}', 'UserController@detachFavoriteMovie')
        ->name('users.detachFavoriteMovie');

    Route::get('wishlist-movies', 'UserController@searchWishlistMovies')
        ->name('users.searchWishlistMovies');

    Route::post('wishlist-movies/{movie}', 'UserController@attachWishlistMovie')
        ->name('users.attachWishlistMovie');

    Route::delete('wishlist-movies/{movie}', 'UserController@detachWishlistMovie')
        ->name('users.detachWishlistMovie');

    Route::get('comments', 'UserController@searchComments')
        ->name('users.searchComments');
});

Route::apiResource('/users', 'UserController', [
    'parameters' => [
        'users' => 'user',
    ],
    'only' => [
        'store'
    ]
]);
