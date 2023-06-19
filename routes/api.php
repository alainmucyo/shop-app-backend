<?php

use Illuminate\Support\Facades\Route;


Route::post('register', 'App\Http\Controllers\API\AuthController@register');
Route::post('login', 'App\Http\Controllers\API\AuthController@login');

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::post('logout', 'App\Http\Controllers\API\AuthController@logout');
    // me route
    Route::get('me', 'App\Http\Controllers\API\AuthController@me');
    Route::apiResource('products', 'App\Http\Controllers\API\ProductController');


    // The BasketItem routes
    Route::get('/basket/items', 'App\Http\Controllers\API\BasketItemController@index')->name('basket-items.index');
    Route::post('/basket/items', 'App\Http\Controllers\API\BasketItemController@store')->name('basket-items.store');
    Route::get('/basket/items/{basketItem}', 'App\Http\Controllers\API\BasketItemController@show')->name('basket-items.show');
    Route::delete('/basket/items/{basketItem}', 'App\Http\Controllers\API\BasketItemController@destroy')->name('basket-items.destroy');

    // Other authenticated routes can be added here
});
