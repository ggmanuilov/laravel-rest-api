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


Route::group(['prefix' => 'v1/links'], function() {
    Route::get('/', 'ShortUrlsController@index')->name('links');
    Route::get('/resolve/{shortUrl}', 'ShortUrlsController@resolve')->name('links.resolve');
    Route::get('/{linkId}', 'ShortUrlsController@show')->name('links.show');
    Route::post('/', 'ShortUrlsController@store')->name('links.store');

    Route::put('/{id}', 'ShortUrlsController@update')->name('links.update');
    Route::delete('/{id}', 'ShortUrlsController@destroy')->name('links.delete');
});

