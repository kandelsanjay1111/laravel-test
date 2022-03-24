<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('/welcome');
});

Route::get('/send-email',[App\Http\Controllers\MailController::class,'sendEmail']);
Route::get('/mail',[App\Http\Controllers\EmailController::class,'sendMail']);
Route::get('/test',[App\Http\Controllers\MailController::class,'test']);