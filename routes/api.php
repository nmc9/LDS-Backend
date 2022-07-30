<?php

use App\Http\Controllers\AuthProfileController;
use App\Http\Controllers\AvailabilityController;
use App\Http\Controllers\BringableController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\FriendController;
use App\Http\Controllers\ImaginaryFriendController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\ProfileController;
use App\Library\Invitations\InvitationMailService;
use App\Mail\RegisterMail;
use App\Models\Event;
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
Route::get('event',[EventController::class,'index']);
Route::put('event/{event}',[EventController::class,'update']);


Route::get('friend',[FriendController::class,'index']);
Route::get('search/friend',[FriendController::class,'search']);
Route::delete('friend/{user}',[FriendController::class,'remove']);


Route::get('event/{event}/available',[InvitationController::class,'listAvailable']);
Route::get('event/{event}/accepted',[InvitationController::class,'accepted']);
Route::get('event/{event}/pending',[InvitationController::class,'pending']);


Route::post('friend',[FriendController::class,'store']);
Route::post('imaginary/friend',[ImaginaryFriendController::class,'store']);


Route::post('event/{event}/invitation',[InvitationController::class,'store']);


Route::post('event/{event}/bringable',[BringableController::class,'store']);
Route::get('event/{event}/bringable',[BringableController::class,'index']);
Route::get('event/{event}/user/{user}/bringable',[BringableController::class,'user_index']);



//Update the name/notes/importance
//Update bringable – id, data
Route::put('bringable/{bringable}',[BringableController::class,'update']);

//Delete entire bringable object and children
//Delete bringable - id
Route::delete('bringable/{bringable}',[BringableController::class,'destory']);

//Delete all items, create a new one with unassigned.
//Delete all items – id <sum required, clear acquired, set unassigned>
Route::post('bringable/clearaquired/{bringable}',[BringableController::class,'clearaquired']);


//Get list of items from a bringable
//Search bringable items <> id,
Route::get('bringable/{bringable}/items',[BringableItemController::class,'index']);


//Create an item for this bringable
//Create bringable item –data
Route::post('bringable/{bringable}/items',[BringableItemController::class,'create']);


//reassign user on an item (one parameter is (KEEP -> should it keep the assigned)
//Reassign bringable item (keep acquired, remove acquired) – item_id, user_id, keep:Boolean
Route::post('bringableitem/reassign/{bringable_item}',[BringableItemController::class,'reassign']);

//Update the required or acquired count for an item.
//Update bringable item count – item_id, data
Route::put('bringableitem/{bringable_item}',[BringableItemController::class,'update']);

//Delete the bringableitem completely
//Delete bringable item – item_id
Route::delete('bringableitem/{bringable_item}',[BringableItemController::class,'destroy']);






//TEST
Route::get('mail',function(InvitationMailService $serivce){
    $serivce->sendRequestMail(User::first(),User::first(),Event::first(),"XXX");
    $serivce->sendReminderMail(User::first(),User::first(),Event::first(),"XXX");
});