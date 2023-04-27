@component('mail::message')
<h2>Hello {{$data['user_name']}},</h2>
<p>Your invitation to Join {{ $data['organization_name'] }} has been {{($data['action'] == 'approve')?'Accepted':'Rejected'}} by {{ $data['sender_name']}}.</p> 
 
Thanks,<br>
Team {{$data['organization_name']}}
@endcomponent