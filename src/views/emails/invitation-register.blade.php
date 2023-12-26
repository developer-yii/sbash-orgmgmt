@component('mail::message')
# {{__('orgmgmt')['mails']['hello']}},

{{__('orgmgmt')['mails']['invited_to_join']}} {{ $data['organization_name'] }} {{__('orgmgmt')['mails']['by']}} {{ $data['user_name'] }}. 

@if($data['invite_message'])
<p>{{__('orgmgmt')['mails']['following_is_inviter_note']}}:</p>                           
<p><i>{{$data['invite_message']}}<i></p> 
@endif

{{__('orgmgmt')['mails']['invitation_register_text1']}}:

@component('mail::button', ['url' => route('register').'?email='.$data['email']])
{{__('orgmgmt')['mails']['btn']['register']}}
@endcomponent

{{__('orgmgmt')['mails']['invitation_register_text2']}} {{$data['organization_email']}}.

{{__('orgmgmt')['mails']['thanks']}},<br>
{{__('orgmgmt')['mails']['team']}} {{$data['organization_name']}}.
@endcomponent
