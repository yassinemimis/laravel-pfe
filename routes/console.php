<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use App\Models\EmailTemplate;
use App\Mail\SendEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schedule;
use Carbon\Carbon;
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::call(function () {
try {
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
    else if($template->recipient=="etudiant5"){


    $emails = DB::table('theme_pf')
    ->join('ententreprise', 'theme_pf.id_entreprise', '=', 'ententreprise.id_entreprise')
    ->join('utilisateur_pf', 'utilisateur_pf.id_utilisateur', '=', 'ententreprise.id_utilisateur')
    ->where('theme_pf.depse', 'Entreprise')
    ->whereNull('theme_pf.encadrant_president')
    ->pluck('utilisateur_pf.adresse_email');

        foreach ($emails as $adresse_email) {
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
    $template->update(['is_sent' => true, 'sent_at' => now()]);
}
foreach ($emailTemplatesreminder as $template) {
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
    else if($template->recipient=="ententreprise"){
        
        $result = DB::table('utilisateur_pf')
        ->join('ententreprise', 'utilisateur_pf.id_utilisateur', '=', 'ententreprise.id_utilisateur')
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
    } catch (\Exception $e) {
        \Log::error('Failed to send email: ' . $e->getMessage());
    }
})->everyMinute();