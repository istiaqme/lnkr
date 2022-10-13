<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\APIController;

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

Route::group(['middleware' => 'APIAuthentication'], function(){
    Route::get('/', [APIController::class, 'index']);
});

// handle all 404
Route::fallback(function(){
    return response()->json(
        [
            'status' => 'error',
            'message' => 'Wrong URL.'
        ], 
    404);
});
