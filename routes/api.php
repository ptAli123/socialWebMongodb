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


Route::post('/post',[postController::class,'post'])->middleware('userAuth');
Route::post('/post-update',[postController::class,'postUpdate'])->middleware("userAuth");
Route::post('/post-search',[postController::class,'postSearch'])->middleware("userAuth");
Route::post('/post-delete',[postController::class,'postDelete'])->middleware("userAuth");


Route::post('/list-view',[listViewController::class,'postList'])->middleware("userAuth");

Route::post('/comment',[commentController::class,'comment'])->middleware("userAuth");
Route::post('/comment-update',[commentController::class,'commentUpdate'])->middleware("userAuth");
Route::post('/comment-delete',[commentController::class,'commentDelete'])->middleware("userAuth");

Route::post('/friend',[friendController::class,'friend'])->middleware("userAuth");
Route::post('/friend-remove',[friendController::class,'friendRemove'])->middleware("userAuth");