<?php

namespace Sbash\Orgmgmt\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvitationActionMail extends Mailable
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
        if($this->data['action'] == 'reject')
            $subject1 = str_replace('<<Organization name>>', $this->data['organization_name'], __('orgmgmt')['mails']['invite_response_subject_rejected']);
        elseif($this->data['action'] == 'approve')
            $subject1 = str_replace('<<Organization name>>', $this->data['organization_name'], __('orgmgmt')['mails']['invite_response_subject_accepted']);
        

        $markDownView = 'orgmgmt::emails.invitation-action';

        if($this->data['organization_id'] && config('app.up_organization_id') && $this->data['organization_id'] == config('app.up_organization_id')){
            app()->setLocale('de');
            $markDownView = 'orgmgmt::emails.uplandcare.invitation-action';
        }

        return $this->from($this->fromEmail)
               ->subject($subject1)
               ->markdown($markDownView)
               ->with(['data' => $this->data]);
    }
}