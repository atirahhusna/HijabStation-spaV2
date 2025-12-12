<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ImageUploadController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Image upload API route
Route::post('/upload-image', [ImageUploadController::class, 'upload']);

// Receive data API route
Route::post('/receive-data', [ImageUploadController::class, 'receiveData']);

// Get received data API route
Route::get('/get-received-data', [ImageUploadController::class, 'getReceivedData']);

// Clear received data
Route::post('/clear-received-data', [ImageUploadController::class, 'clearReceivedData']);
