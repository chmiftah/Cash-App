<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\MeController;
use App\Http\Controllers\CashController;

// Auth::loginUsingId(1);
Route::middleware('auth:sanctum')->group(function(){
    Route::get('me', [MeController::class,'__invoke']);

    Route::prefix('cash')->group(function(){
        Route::get('', [CashController::class,'index']);
        Route::post('create', [CashController::class,'store']);
        Route::get('{cash:slug}', [CashController::class,'show']);
    });
});

