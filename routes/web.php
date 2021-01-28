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

Route::post('/login/token', 'AuthController@index')->name('loginToken');

Route::get('/', 'Auth\LoginController@loginForm')->name('login.form');
Route::post('/loginweb', 'Auth\LoginController@login')->name('login.web');
Route::get('/logout', 'Auth\LoginController@logout')->name('logout');

Route::get('/clients', 'ClientController@index')->middleware('isAuth')->name('clients');
Route::get('/client/sale/{idclient}', 'ClientController@createSale')->middleware('isAuth')->name('clients-sales');

Route::get('/client/store/products', 'ClientController@storeProducts')->middleware('isAuth');
Route::post('/client/store/products', 'ClientController@storeProducts')->middleware('isAuth');
