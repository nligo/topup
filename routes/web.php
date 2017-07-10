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
Route::get('/test', 'IndexController@test');
Route::post('/pay', 'IndexController@pay');
Route::post('/store', 'IndexController@store');
Route::get('/return', 'IndexController@webReturn');
Route::get('/notify', 'IndexController@webNotify');

Route::group(['prefix' => 'order'],function (){
    Route::get('/request', 'OrderController@request')->name('order_request');
    Route::post('/store', 'OrderController@store')->name('order_store');
    Route::get('/{orderId}/pay', 'OrderController@pay')->name('order_pay');
    Route::get('/return', 'OrderController@webReturn')->name('order_return');
    Route::get('/notify', 'OrderController@notify')->name('order_notify');
});

