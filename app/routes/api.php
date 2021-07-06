<?php

use Illuminate\Http\Request;

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

Route::put('/get-api-token', 'Api\ApiController@index')->name('get_api_token');

Route::middleware('auth:api')->group(function () {
    Route::put('/get-prize', 'Api\ApiController@getPrize')->name('get_prize');
//    Route::put('/get-prize', 'Api\ApiController@paidOut')->name('get_prize');
});
