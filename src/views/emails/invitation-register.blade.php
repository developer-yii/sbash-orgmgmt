@component('mail::message')
# {{('orgmgmt')['mails']['hello']}},

{{('orgmgmt')['mails']['invited_to_join']}} {{ $data['organization_name'] }} {{('orgmgmt')['mails']['by']}} {{ $data['user_name'] }}. 

<p>{{('orgmgmt')['mails']['following_is_inviter_note']}}:</p>                           
<p><i>{{$data['invite_message']}}<i></p> 

{{('orgmgmt')['mails']['invitation_register_text1']}}:

@component('mail::button', ['url' => route('register').'?email='.$data['email']])
{{('orgmgmt')['mails']['btn']['register']}}
@endcomponent

{{('orgmgmt')['mails']['invitation_register_text2']}} {{$data['organization_email']}}.

{{('orgmgmt')['mails']['thanks']}},<br>
{{('orgmgmt')['mails']['team']}} {{$data['organization_name']}}.
@endcomponent
