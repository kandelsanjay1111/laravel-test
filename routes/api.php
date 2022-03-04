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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });


// Route::get('/tickets',[App\Http\Controllers\Api\TicketController::class,'tickets']);
Route::get('/test',function(){
	return response()->json(['message'=>'lsklsklskld']);
});

Route::middleware('auth:api')->group(function(){
Route::get('/ticket/{id}',[App\Http\Controllers\Api\TicketController::class,'getTicket']);
Route::get('/source',[App\Http\Controllers\Api\TicketController::class,'index']);
Route::post('/source',[App\Http\Controllers\Api\TicketController::class,'store']);
Route::get('/source/{id}',[App\Http\Controllers\Api\TicketController::class,'show']);
Route::put('/source/update/{id}',[App\Http\Controllers\Api\TicketController::class,'update']);
Route::delete('/source/{id}',[App\Http\Controllers\Api\TicketController::class,'destroy']);
Route::get('/user',[App\Http\Controllers\Api\UserController::class,'index']);
Route::post('/user',[App\Http\Controllers\Api\UserController::class,'create']);
Route::get('/user/{id}',[App\Http\Controllers\Api\UserController::class,'show']);
Route::put('/user/{id}',[App\Http\Controllers\Api\UserController::class,'update']);
Route::delete('/user/{id}',[App\Http\Controllers\Api\UserController::class,'destroy']);

});
Route::post('/login',[App\Http\Controllers\Api\AuthController::class,'login'])->name('login');
