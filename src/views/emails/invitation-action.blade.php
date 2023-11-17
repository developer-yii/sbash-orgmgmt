@component('mail::message')
<h2>{{('orgmgmt')['mails']['hello']}} {{$data['user_name']}},</h2>
<p>{{('orgmgmt')['mails']['invitation_to_join']}} {{ $data['organization_name'] }} {{('orgmgmt')['mails']['has_been']}} {{($data['action'] == 'approve')?'Accepted':'Rejected'}} {{('orgmgmt')['mails']['by']}} {{ $data['sender_name']}}.</p> 
 
{{('orgmgmt')['mails']['thanks']}},<br>
{{('orgmgmt')['mails']['team']}} {{$data['organization_name']}}
@endcomponent