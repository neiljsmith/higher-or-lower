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

Route::get('/game/over', 'GameController@over');
Route::get('/game/new', 'GameController@new');
Route::get('/game/guess', 'GameController@guess');
Route::get('/game/guess/{action}', 'GameController@guess');
