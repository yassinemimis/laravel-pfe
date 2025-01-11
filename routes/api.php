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
use App\Models\EmailTemplate;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendEmail;
use Carbon\Carbon;

Route::get('/test', function () {
    return response()->json(['message' => 'API is aaaaaaaaaa working!']);
});


Route::post('/import-users', [UserController::class, 'import']);



Route::get('/email-templates', [EmailTemplateController::class, 'index']);
Route::post('/email-templates', [EmailTemplateController::class, 'store']);
Route::get('/email-templates/{id}', [EmailTemplateController::class, 'show']);
Route::put('/email-templates/{id}', [EmailTemplateController::class, 'update']);
Route::delete('/email-templates/{id}', [EmailTemplateController::class, 'destroy']);

Route::get('/etudiant', [EtudiantController::class, 'index']);
Route::post('/etudiant', [EtudiantController::class, 'store']);
Route::get('/etudiant/{id}', [EtudiantController::class, 'show']);
Route::put('/etudiant/{id}', [EtudiantController::class, 'update']);
Route::delete('/etudiant/{id}', [EtudiantController::class, 'destroy']);
 
Route::post('/assign-multiple-projects', [ThemePfController::class, 'assignMultipleProjects']);


Route::get('/themes', [ThemePfController::class, 'index']);
Route::post('/themes', [ThemePfController::class, 'store']);
Route::get('/themes/{id}', [ThemePfController::class, 'show']);
Route::put('/themes/{id}', [ThemePfController::class, 'update']);
Route::put('/themes1/{id}', [ThemePfController::class, 'update1']);
Route::delete('/themes/{id}', [ThemePfController::class, 'destroy']);
Route::get('/encadrant', [ThemePfController::class, 'getWithoutEncadrantPresident']);
Route::get('/enattente', [ThemePfController::class, 'getPendingProjects']);
Route::get('/enattente1', [ThemePfController::class, 'getPendingProjects1']);
Route::get('/enattente2', [ThemePfController::class, 'getPendingProjects2']);

Route::get('/enseignants', [EnseignantController::class, 'index']);
Route::post('/enseignants', [EnseignantController::class, 'store']);
Route::get('/enseignants/{id}', [EnseignantController::class, 'show']);
Route::put('/enseignants/{id}', [EnseignantController::class, 'update']);
Route::delete('/enseignants/{id}', [EnseignantController::class, 'destroy']);
Route::get('/co-encadrants', [EnseignantController::class, 'getCoEncadrants']);
Route::get('/co-etudiant', [EtudiantController::class, 'getEtudiant']);



Route::post('/login', [AuthController::class, 'login']);



Route::post('/sendemailtemplates', function () {

   $emailTemplates = EmailTemplate::whereDate('send_date', Carbon::today())
        ->get();

    foreach ($emailTemplates as $template) {
      
       
        if($template->recipient=="etudiant"){

            $result = DB::table('utilisateur_pf')
            ->join('etudiant', 'utilisateur_pf.id_utilisateur', '=', 'etudiant.id_utilisateur')
            ->select('adresse_email') 
            ->get();
            foreach ($result as $adresse_email) {
                Mail::to($adresse_email->adresse_email)->send(new SendEmail($template));
            }
        }
        else if($template->recipient=="enseignant"){
            
            $result = DB::table('utilisateur_pf')
            ->join('enseignant', 'utilisateur_pf.id_utilisateur', '=', 'enseignant.id_utilisateur')
            ->select('adresse_email') 
            ->get();
            foreach ($result as $adresse_email) {
                Mail::to($adresse_email->adresse_email)->send(new SendEmail($template));
            }
        }
        
    }

    return response()->json(['status' => $emailTemplates]);
});
