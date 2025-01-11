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
    } catch (\Exception $e) {
        \Log::error('Failed to send email: ' . $e->getMessage());
    }
})->everyMinute();