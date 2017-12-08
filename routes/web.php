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

//Auth::routes();

Route::get('/auth0/callback', '\Auth0\Login\Auth0Controller@callback');

Route::post('/auth/logout', 'Auth\LoginController@logout')->name('logout');
//Route::get('/auth/logout', 'Auth\LoginController@logout')->name('logout');

Route::get('/auth/login', 'Auth\LoginController@login')->name('login');

Route::get('/home', 'HomeController@index')->name('home')->middleware('auth');

// ---
// Internal Receipts
// ---

Route::resource('/internalreceipts', 'InternalReceiptController')->middleware('auth');