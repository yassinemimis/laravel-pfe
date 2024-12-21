<?php

namespace App\Console\Commands;

use App\Models\EmailTemplate;
use App\Mail\EmailTemplateMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendAutomaticEmails extends Command
{
    protected $signature = 'send:emails';
    protected $description = 'Envoyer des e-mails automatiques aux destinataires spécifiés';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // Récupérer les templates d'emails programmés
        $templates = EmailTemplate::where('send_date', '<=', now())
                                  ->where('send_date', '>', now()->subMinute())
                                  ->get();

        foreach ($templates as $template) {
            $recipients = $this->getRecipients($template->recipient);
            
            foreach ($recipients as $recipient) {
                // Envoyer l'email
                Mail::to($recipient)->send(new EmailTemplateMail($template));
            }
        }

        $this->info('E-mails envoyés avec succès !');
    }

    // Fonction pour récupérer les destinataires selon le type
    private function getRecipients($recipientType)
    {
        switch ($recipientType) {
            case 'Étudiants':
                // Logique pour récupérer les étudiants
                return ['student@example.com'];
            case 'Entreprises':
                // Logique pour récupérer les entreprises
                return ['company@example.com'];
            case 'Enseignants':
                // Logique pour récupérer les enseignants
                return ['teacher@example.com'];
            default:
                return [];
        }
    }
}
