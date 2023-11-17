@component('mail::message')
<h2>{{('orgmgmt')['mails']['hello']}} {{$data['user_name']}},</h2>
<p>{{('orgmgmt')['mails']['request_to_join']}} {{ $data['organization_name'] }} {{('orgmgmt')['mails']['has_been_recieved_from']}} {{$data['sender_name']}}</p>
 
<p>{{('orgmgmt')['mails']['kindly_take_action']}}</p>

@component('mail::button', ['url' => route('organization.request.list')])
{{('orgmgmt')['mails']['btn']['join_request']}}
@endcomponent
 
Thanks,<br>
{{$data['organization_name']}}<br>
@endcomponent