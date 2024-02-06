<?php

namespace Sbash\Orgmgmt\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InviteNonRegisteredUserMail extends Mailable
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
        $subject1 = str_replace('<<Organization Name>>', $this->data['organization_name'], __('orgmgmt')['mails']['invite_subject']);

        $markDownView = 'orgmgmt::emails.invite';

        if($this->data['organization_id'] && config('app.up_organization_id') && $this->data['organization_id'] == config('app.up_organization_id')){
            app()->setLocale('de');
            $markDownView = 'orgmgmt::emails.uplandcare.invite';
        }
        
        return $this->from($this->data['organization_email'])
               ->subject($subject1)
               ->markdown($markDownView)
               ->with(['data' => $this->data]);
    }
}