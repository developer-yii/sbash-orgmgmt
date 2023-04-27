@component('mail::message')
<h2>Hello {{$data['userName']}},</h2>
<p>Your request to Join {{ $data['name'] }} has been {{$data['action']}}.</p> 
<p>following is owners note:</p>                           
<p><i>{{$data['note']}}<i></p> 
 
Thanks,<br>
<p>Team {{$data['name']}}</p>
@endcomponent