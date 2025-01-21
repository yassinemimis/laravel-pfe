<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EtudiantController;
use Illuminate\Http\Request;
use App\Http\Controllers\EmailTemplateController;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\ThemePfController;
use App\Http\Controllers\EnseignantController;
use App\Http\Controllers\EntrepriseController;
use App\Http\Controllers\AuthController;
use App\Models\EmailTemplate;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendEmail;
use Carbon\Carbon;
use App\Http\Controllers\ChoixUnController;
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
Route::post('/projects', [ThemePfController::class, 'getChoixEtudiant']); 
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
Route::get('/etudiant4', [EtudiantController::class, 'getEtudiant4']);
Route::get('/enseignant4', [EnseignantController::class, 'getEnseignant4']);
Route::get('/entreprise4', [EntrepriseController::class, 'getEntreprise4']);

Route::get('/choix', [ChoixUnController::class, 'index']); 
Route::post('/choix', [ChoixUnController::class, 'store']); 
Route::get('/choix/{id}', [ChoixUnController::class, 'show']); 
Route::put('/choix/{id}', [ChoixUnController::class, 'update']); 
Route::delete('/choix/{id}', [ChoixUnController::class, 'destroy']);
Route::post('/choixBinome', [ChoixUnController::class, 'getBinome']); 
Route::post('/login', [AuthController::class, 'login']);

Route::get('/entreprise', [EntrepriseController::class, 'index']);
Route::post('/entreprise', [EntrepriseController::class, 'store']);
Route::get('/entreprise/{id}', [EntrepriseController::class, 'show']);
Route::put('/entreprise/{id}', [EntrepriseController::class, 'update']);
Route::delete('/entreprise/{id}', [EntrepriseController::class, 'destroy']);

Route::post('/sendemailtemplates', function () {

    $emailTemplates = EmailTemplate::where('is_sent', false)
    ->whereDate('send_date', Carbon::today())
    ->get();
    $emailTemplatesreminder = EmailTemplate::where('is_reminder', false)
    ->whereDate('reminder_date', Carbon::today())
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
    else if($template->recipient=="enseignant1"){
        
        $students = DB::table('enseignant as e')
        ->join('utilisateur_pf as a', 'a.id_utilisateur', '=', 'e.id_utilisateur')
        ->leftJoin('theme_pf as t', 'e.id_ens', '=', 't.encadrant_president')
        ->whereNull('t.encadrant_president')
        ->select('e.id_ens', 'a.adresse_email')
        ->get();
    
        foreach ($students as $adresse_email) {
            Mail::to($adresse_email->adresse_email)->send(new SendEmail($template));
        }
    }
    $template->update(['is_reminder' => true, 'sent_at' => now()]);
    
}

    return response()->json(['status' => $emailTemplates]);
});
