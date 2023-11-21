@component('mail::message')
<h2>{{__('orgmgmt')['mails']['hello']}} {{$data['user_name']}},</h2>
<p>{{__('orgmgmt')['mails']['request_to_join']}} {{ $data['organization_name'] }} {{__('orgmgmt')['mails']['has_been_recieved_from']}} {{$data['sender_name']}}</p>
 
<p>{{__('orgmgmt')['mails']['kindly_take_action']}}</p>

@component('mail::button', ['url' => route('organization.request.list')])
{{__('orgmgmt')['mails']['btn']['join_request']}}
@endcomponent
 
Thanks,<br>
{{$data['organization_name']}}<br>
@endcomponent