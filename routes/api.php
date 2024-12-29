<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EtudiantController;
use Illuminate\Http\Request;
use App\Http\Controllers\EmailTemplateController;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\ThemePfController;
use App\Http\Controllers\EnseignantController;
use App\Http\Controllers\AuthController;

Route::get('/test', function () {
    return response()->json(['message' => 'API is aaaaaaaaaa working!']);
});


Route::post('/import-users', [UserController::class, 'import']);



Route::get('/email-templates', [EmailTemplateController::class, 'index']);
Route::post('/email-templates', [EmailTemplateController::class, 'store']);
Route::get('/email-templates/{id}', [EmailTemplateController::class, 'show']);
Route::put('/email-templates/{id}', [EmailTemplateController::class, 'update']);
Route::delete('/email-templates/{id}', [EmailTemplateController::class, 'destroy']);

Route::get('/teachers', [EtudiantController::class, 'index']);
Route::post('/teachers', [EtudiantController::class, 'store']);
Route::get('/teachers/{id}', [EtudiantController::class, 'show']);
Route::put('/teachers/{id}', [EtudiantController::class, 'update']);
Route::delete('/teachers/{id}', [EtudiantController::class, 'destroy']);
 
Route::post('/assign-multiple-projects', [ThemePfController::class, 'assignMultipleProjects']);


Route::get('/themes', [ThemePfController::class, 'index']);
Route::post('/themes', [ThemePfController::class, 'store']);
Route::get('/themes/{id}', [ThemePfController::class, 'show']);
Route::put('/themes/{id}', [ThemePfController::class, 'update']);
Route::delete('/themes/{id}', [ThemePfController::class, 'destroy']);
Route::get('/encadrant', [ThemePfController::class, 'getWithoutEncadrantPresident']);

Route::get('/enseignants', [EnseignantController::class, 'index']);
Route::post('/enseignants', [EnseignantController::class, 'store']);
Route::get('/enseignants/{id}', [EnseignantController::class, 'show']);
Route::put('/enseignants/{id}', [EnseignantController::class, 'update']);
Route::delete('/enseignants/{id}', [EnseignantController::class, 'destroy']);
Route::get('/co-encadrants', [EnseignantController::class, 'getCoEncadrants']);
Route::get('/co-etudiant', [EtudiantController::class, 'getEtudiant']);



Route::post('/login', [AuthController::class, 'login']);