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


Route::post('/post',[postController::class,'post'])->middleware('userAuth');

Route::post('/post-update',[postController::class,'postUpdate'])->middleware("userAuth");
Route::post('/post-search',[postController::class,'postSearch'])->middleware("userAuth");
Route::post('/post-delete',[postController::class,'postDelete'])->middleware("userAuth");