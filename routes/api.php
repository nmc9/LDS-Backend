<?php

use App\Http\Controllers\AuthProfileController;
use App\Http\Controllers\AvailabilityController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\ImaginaryFriendController;
use App\Http\Controllers\ProfileController;
use App\Mail\RegisterMail;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

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

Route::get('/example',function(){
    \Mail::to("nmc9@pct.edu")->send(new RegisterMail("Nicholas Caruso"));

    return [
        "id"=> 1,
        "name" => "John Doe",
        "token" => \Str::uuid()
    ];
});

Route::post('login',[AuthProfileController::class,'login'])->name('sanctum.login');
Route::post('logout',[AuthProfileController::class,'logout'])->name('sanctum.logout');
Route::post('forgot-password',[AuthProfileController::class,'forgot'])->name('password.email');
Route::post('reset-password',[AuthProfileController::class,'reset'])->name('password.update');
Route::post('register',[AuthProfileController::class,'register'])->name('profile.register');

Route::get('profile',[ProfileController::class,'index']);
Route::get('search/profile',[ProfileController::class,'search']);

Route::get('availability',[AvailabilityController::class,'index']);

Route::post('event',[EventController::class,'store']);
Route::get('event/{event}',[EventController::class,'show']);

Route::post('friend',[FriendController::class,'store']);
Route::post('imaginary/friend',[ImaginaryFriendController::class,'store']);
