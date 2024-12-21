<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EtudiantController;
use Illuminate\Http\Request;
use App\Http\Controllers\EmailTemplateController;
use Illuminate\Support\Facades\Log;
Route::get('/test', function () {
    return response()->json(['message' => 'API is aaaaaaaaaa working!']);
});


Route::post('/import-users', [UserController::class, 'import']);
Route::apiResource('/teachers', EtudiantController::class);
Route::delete('/teachers/{id_utilisateur}', [YourController::class, 'destroy']);


Route::get('/email-templates', [EmailTemplateController::class, 'index']);
Route::post('/email-templates', [EmailTemplateController::class, 'store']);
Route::get('/email-templates/{id}', [EmailTemplateController::class, 'show']);
Route::put('/email-templates/{id}', [EmailTemplateController::class, 'update']);
Route::delete('/email-templates/{id}', [EmailTemplateController::class, 'destroy']);
