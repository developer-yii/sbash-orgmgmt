@component('mail::message')
<h2>{{__('orgmgmt')['mails']['hello']}} {{$data['user_name']}},</h2>
<p>{{__('orgmgmt')['mails']['invitation_to_join']}} {{ $data['organization_name'] }} {{__('orgmgmt')['mails']['has_been']}} {{($data['action'] == 'approve')?'Accepted':'Rejected'}} {{__('orgmgmt')['mails']['by']}} {{ $data['sender_name']}}.</p> 
 
{{__('orgmgmt')['mails']['thanks']}},<br>
{{__('orgmgmt')['mails']['team']}} {{$data['organization_name']}}
@endcomponent