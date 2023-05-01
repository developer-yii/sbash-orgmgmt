<?php

namespace Sbash\Orgmgmt\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InviteNonRegisteredMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $data;
    public $fromEmail;

    public function __construct($data,$fromEmail)
    {
        $this->data = $data;
        $this->fromEmail = $fromEmail;
    }

    public function build()
    {        
        $subject1 = 'Invitation to Register on'.config('app.name').'.';
        return $this->from($this->fromEmail)
               ->subject($subject1)
               ->markdown('orgmgmt::emails.invitation-register')
               ->with(['data' => $this->data]);
    }
}