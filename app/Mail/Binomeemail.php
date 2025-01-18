<?php

namespace App\Mail;

use App\Models\EmailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Binomeemail extends Mailable
{
    use Queueable, SerializesModels;

  

    public function build()
    {
        return $this->view('emails.test');
    }
}
