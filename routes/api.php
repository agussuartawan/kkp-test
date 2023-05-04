<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ShipController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'api'], function (){

    Route::group(['prefix' => 'auth'], function () {
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::post('me', [AuthController::class, 'me']);
    });

    Route::group(['middleware' => 'can:verificate ship'], function(){
        Route::get('ships', [ShipController::class, 'getAll']);
        Route::post('ships/{ship}/{verif}', [ShipController::class, 'verifShip']);
    });
    Route::group(['middleware' => 'can:edit ship'], function(){
        Route::put('ships/{ship}', [ShipController::class, 'update']);
    });
    Route::group(['middleware' => 'can:delete ship'], function(){
        Route::delete('ships/{ship}', [ShipController::class, 'delete']);
    });

    Route::post('ships', [ShipController::class, 'create']);
    Route::group(['middleware' => 'can:verificate account'], function (){
        Route::get('users', [UserController::class, 'getUserVerified']);
        Route::put('users/{user}/{verif}', [UserController::class, 'verifUser']);
    });
    Route::group(['middleware' => 'can:edit account'], function(){
        Route::put('users/{user}', [UserController::class, 'update']);
    });
    Route::group(['middleware' => 'can:delete account'], function(){
        Route::delete('users/{user}', [UserController::class, 'delete']);
    });

    Route::put('users/me/{user}', [UserController::class, 'updateMe']);

});

Route::group(['prefix' => 'auth'], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [RegisterController::class, 'register']);
    Route::post('register/request-otp', [RegisterController::class, 'requestOtp']);
    Route::post('register/verificate', [RegisterController::class, 'verificateAccount']);
});

Route::get('ship-all', [ShipController::class, 'shipAllPublic']);
