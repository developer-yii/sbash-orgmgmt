@component('mail::message')
# {{('orgmgmt')['mails']['hello']}},

{{('orgmgmt')['mails']['invitation_to_join']}} {{ $data['organization_name'] }} {{('orgmgmt')['mails']['by']}} {{ $data['user_name'] }}.

<p>{{('orgmgmt')['mails']['following_is_inviter_note']}}:</p>                           
<p><i>{{$data['invite_message']}}<i></p> 

{{('orgmgmt')['mails']['kindly_take_action']}}

<div style="display: inline-block">
@component('mail::button', ['url' => $data['urlApprove'], 'color' => 'success'])
{{('orgmgmt')['mails']['btn']['accept']}}
@endcomponent
</div>

<div style="display: inline-block; margin-left: 10px">
@component('mail::button', ['url' => $data['urlReject'], 'color' => 'error'])
{{('orgmgmt')['mails']['btn']['reject']}}
@endcomponent
</div>

{{('orgmgmt')['mails']['thanks']}},<br>
{{('orgmgmt')['mails']['team']}} {{$data['organization_name']}}
@endcomponent
