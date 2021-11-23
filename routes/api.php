<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\signUpController;
use App\Http\Controllers\loginController;
use App\Http\Controllers\mailConfirmationController;
use App\Http\Controllers\postController;
use App\Http\Controllers\commentController;
use App\Http\Controllers\friendController;
use App\Http\Controllers\listViewController;
use App\Http\Controllers\UserForgetPasswordController;

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

Route::post('/sign-up',[signUpController::class,'signUp']);
Route::get('/mail-confirmation/{email}/{varify_token}',[mailConfirmationController::class,'confirmed']);

Route::post('/login',[loginController::class,'login']);
Route::post('/logout',[loginController::class,'logout'])->middleware("userAuth");

Route::post('/forget-password',[UserForgetPasswordController::class, 'forgetPasword']);
Route::post('/forget-password-update',[UserForgetPasswordController::class, 'updatePassword']);
