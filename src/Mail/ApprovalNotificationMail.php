<?php

namespace Sbash\Orgmgmt\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ApprovalNotificationMail extends Mailable
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
        $subject1 = 'Request received to Join'.$this->data['organization_name'];
        return $this->from($this->fromEmail)
               ->subject($subject1)
               ->markdown('orgmgmt::emails.approval-notification')
               ->with(['data' => $this->data]);
    }
}