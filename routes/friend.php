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

Route::post('/friend',[friendController::class,'friend'])->middleware("userAuth");
Route::post('/friend-remove',[friendController::class,'friendRemove'])->middleware("userAuth");