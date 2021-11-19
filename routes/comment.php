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

Route::post('/comment',[commentController::class,'comment'])->middleware("userAuth");
Route::post('/comment-update',[commentController::class,'commentUpdate'])->middleware("userAuth");
Route::post('/comment-delete',[commentController::class,'commentDelete'])->middleware("userAuth");