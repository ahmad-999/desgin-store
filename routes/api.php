<?php

use App\Http\Controllers\DesginController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Traits\MyResponse;
use \App\Traits\Constance;
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

Route::middleware('auth:sanctum')->post('/me', function (Request $request) {
    return $request->user();
});
Route::get('auth_error', function () {
    return MyResponse::returnError('not logged in', 200);
})->name('login');
Route::post('/login', [UserController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum', 'is_admin']], function () {
    Route::post('create-tag', [UserController::class, 'createTag']);
    Route::post('create-distributor', [UserController::class, 'createDistributor']);
    Route::post('delete-distributor', [UserController::class, 'deleteDistributor']);
    Route::post('delete-tag/{id}', [UserController::class, 'deleteTag']);
    
    Route::post('get-distributor/{id}', [UserController::class, 'getDistributorById']);
   
});

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('create-desgin', [DesginController::class, 'createDesgin']);
    Route::post('delete-desgin', [DesginController::class, 'deleteDesgin']);
    Route::post('update-desgin', [DesginController::class, 'updateDesgin']);
    Route::post('update-desgin-tags', [DesginController::class, 'updateDesginTags']);
    Route::post('logout', [UserController::class, 'logout']);
    Route::post('get-my-designs',[DesginController::class,'getMyDesigns']);
    Route::post('/me',[UserController::class,'me']);
    Route::post('update-distributor', [UserController::class, 'updateDistributor']);
});

Route::post('/get-all-designs',[DesginController::class,'getAllDesigns']);
Route::post('/get-all-tags',[DesginController::class,'getAllTags']);
Route::post('/get-all-genre-tags',[DesginController::class,'getAllGenreTags']);
Route::post('/get-all-distributors', [UserController::class, 'getAllDistributor']);
Route::post('/get-desgin-by-tags', [DesginController::class, 'getDesignsByTags']);
