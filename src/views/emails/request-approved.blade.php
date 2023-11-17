@component('mail::message')
<h2>{{('orgmgmt')['mails']['hello']}} {{$data['userName']}},</h2>
<p>{{('orgmgmt')['mails']['your_request_to_join']}} {{ $data['name'] }} {{('orgmgmt')['mails']['has_been']}} {{$data['action']}}.</p> 
<p>{{('orgmgmt')['mails']['owners_note']}}:</p>                           
<p><i>{{$data['note']}}<i></p> 
 
{{('orgmgmt')['mails']['thanks']}},<br>
<p>{{('orgmgmt')['mails']['team']}} {{$data['name']}}</p>
@endcomponent