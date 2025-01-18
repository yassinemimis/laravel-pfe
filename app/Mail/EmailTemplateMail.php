<?php

namespace App\Mail;

use App\Models\EmailTemplate;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailTemplateMail extends Mailable
{
    use Queueable, SerializesModels;

    public $template;

    public function __construct(EmailTemplate $template)
    {
        $this->template = $template;
    }

    public function build()
    {
        return $this->view('emails.template')
            ->subject($this->template->subject)
            ->with([
                'content' => $this->template->content,
            ]);
    }
}
