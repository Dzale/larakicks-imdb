<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| CommentController Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all routes for CommentController. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'comments'], function () {
});

Route::apiResource('/comments', 'CommentController', [
    'parameters' => [
        'comments' => 'comment',
    ],
    'only' => [
        'index','show','store','update','destroy'
    ]
]);
