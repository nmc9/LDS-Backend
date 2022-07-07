<?php

use App\Http\Controllers\WebFriendResponseController;
use Illuminate\Support\Facades\Route;

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
    return view('welcome');
})->name('lds.home');


Route::get('/friend-response',[WebFriendResponseController::class,'response'])->name('friend.response');


// Route::get('/friend-response',[WebFriendResponseController::class,'response'])->name('friend.response');
Route::get('/friend-i-response',[WebFriendResponseController::class,'iresponse'])->name('friend.iresponse');

Route::get('/friend-callback',[WebFriendResponseController::class,'callback'])->name('friend.response.callback');