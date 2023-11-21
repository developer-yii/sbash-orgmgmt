@component('mail::message')
# {{__('orgmgmt')['mails']['hello']}},

{{__('orgmgmt')['mails']['invitation_to_join']}} {{ $data['organization_name'] }} {{__('orgmgmt')['mails']['by']}} {{ $data['user_name'] }}.

<p>{{__('orgmgmt')['mails']['following_is_inviter_note']}}:</p>                           
<p><i>{{$data['invite_message']}}<i></p> 

{{__('orgmgmt')['mails']['kindly_take_action']}}

<div style="display: inline-block">
@component('mail::button', ['url' => $data['urlApprove'], 'color' => 'success'])
{{__('orgmgmt')['mails']['btn']['accept']}}
@endcomponent
</div>

<div style="display: inline-block; margin-left: 10px">
@component('mail::button', ['url' => $data['urlReject'], 'color' => 'error'])
{{__('orgmgmt')['mails']['btn']['reject']}}
@endcomponent
</div>

{{__('orgmgmt')['mails']['thanks']}},<br>
{{__('orgmgmt')['mails']['team']}} {{$data['organization_name']}}
@endcomponent
