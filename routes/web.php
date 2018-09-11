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

Route::get('/', [
    'uses' => 'HomepageController@indexAction'
])->name('homepage');

Route::group(['prefix' => 'basket'], function () {

    Route::get('/', [
        'uses' => 'BasketController@showBasketData'
    ]);

    Route::post('/add', [
        'uses' => 'BasketController@addItem',
    ]);

    Route::post('/remove', [
        'uses' => 'BasketController@removeItem'
    ]);

    Route::post('/clear', [
        'uses' => 'BasketController@clearBasket'
    ]);
});