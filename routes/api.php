<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EtudiantController;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Log;
Route::get('/test', function () {
    return response()->json(['message' => 'API is aaaaaaaaaa working!']);
});


Route::post('/import-users', [UserController::class, 'import']);
Route::apiResource('/teachers', EtudiantController::class);