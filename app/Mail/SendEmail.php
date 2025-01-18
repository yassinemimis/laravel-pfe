<?php

namespace App\Mail;

use App\Models\EmailTemplate;
use Illuminate\Mail\Mailable;

class SendEmail extends Mailable
{
    public $template;

    public function __construct(EmailTemplate $template)
    {
        $this->template = $template;
    }

    public function build()
    {
        return $this->subject($this->template->subject)
                    ->view('emails.template') 
                    ->with([
                        'content' => $this->template->content,
                    ]);
    }
}
