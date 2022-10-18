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
    Route::post('/link-group/create', [APIController::class, 'linkGroupCreate']);
    Route::get('/link-group/list', [APIController::class, 'linkGroupList']);
    Route::get('/link-group/{linkGroupId}/link/list', [APIController::class, 'groupLinks']);

    Route::post('/link/create/{linkGroupId}', [APIController::class, 'createLink']);
    Route::get('/link/{shortKey}/visits', [APIController::class, 'linkVisits']);
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
