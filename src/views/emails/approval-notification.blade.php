@component('mail::message')
<h2>Hello {{$data['user_name']}},</h2>
<p>Request to Join {{ $data['organization_name'] }} has been received from {{$data['sender_name']}}</p>
 
<p>Kindly take necessary action on same</p>
 
Thanks,<br>
{{$data['organization_name']}}<br>
@endcomponent