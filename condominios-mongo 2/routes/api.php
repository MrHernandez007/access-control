<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ResidentesController;
use App\Http\Controllers\AuthController;


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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

//     //Route::apiResource('residentes', ResidenteController::class);

//     Route::get('/residentes/index', [ResidentesController::class,'index']);
//     Route::post('/residentes/store', [ResidentesController::class, 'store']);
//     Route::post('/residentes/{id}/uptade', [ResidentesController::class, 'uptade']);
//     Route::get('/residentes/{id}/show', [ResidentesController::class, 'show']);
//     Route::delete('/residentes/{id}/destroy', [ResidentesController::class, 'destroy']);


//     Route::post('/login', [AuthController::class, 'login']);
//     Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:api');
//     Route::get('/me', [AuthController::class, 'me'])->middleware('auth:api');
//     Route::post('/refresh', [AuthController::class, 'refresh'])->middleware('auth:api'); -->