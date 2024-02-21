<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use  \App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ContentController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('v1')->middleware('auth:sanctum')->group(function() {
    Route::get('/logout', [AuthController::class, 'logout']);
    Route::post('/create-task', [ContentController::class, 'createTask']);
Route::post('/update-task/{id}', [ContentController::class, 'updateTask']);
Route::delete('/delete-task/{id}', [ContentController::class, 'delete']);
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


Route::get('/user', function (Request $request) {
    return $request->user();

})->middleware('auth:sanctum');




