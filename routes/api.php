<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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


Route::post('login', 'UserController@login');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('register', 'UserController@register')->middleware('can:adminOnly');
    Route::post('update/{id}', 'UserController@update')->middleware('can:adminOnly');
    Route::post('test','UserController@test')->middleware('can:adminAndEditor');
});
