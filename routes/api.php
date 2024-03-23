<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['prefix' => 'auth'], function(){
    Route::post('login',[UserController::class,'login']);
});

Route::group(['prefix' => 'user','middleware'=>'jwt.verify'], function() {
    Route::get('checkNumber/{num}', [UserController::class, 'checkNumber']);
    Route::get('checkQr/{qr}', [UserController::class, 'checkQr']);
    Route::get('checkoldNumber/{oldnum}', [UserController::class, 'checkoldNumber']);
    Route::get('getImages/{num}', [UserController::class, 'getImages']);
    Route::get('getCarId/{num}', [UserController::class, 'getCarId']);
    Route::get('getUserId/{phone}', [UserController::class, 'getUserId']);
    Route::post('addCar', [UserController::class, 'addCar']);
    Route::patch('addKilo/{id}', [UserController::class, 'addKilo']);
    Route::post('addImages/{num}',[UserController::class,'addImages']);
    Route::patch('updateCondition',[UserController::class,'updateCondition']);
    Route::get('getMaterialQuantities/{name}',[UserController::class,'getMaterialQuantities']);
    Route::get('getCities',[UserController::class,'getCities']);
    Route::get('getMaterials',[UserController::class,'getMaterials']);
    Route::get('getUserStations/{phone}',[UserController::class,'getUserStations']);
    Route::get('getConditions',[UserController::class,'getConditions']);
    Route::get('getUserData/{id}',[UserController::class,'getUserData']);
    Route::get('getCarsNumber/{oldnum}', [UserController::class, 'getCarsNumber']);
    Route::get('getNotifications', [UserController::class, 'getNotifications']);
});
