@component('mail::message')
<h2>{{__('orgmgmt')['mails']['hello']}} {{$data['userName']}},</h2>
<p>{{__('orgmgmt')['mails']['your_request_to_join']}} {{ $data['name'] }} {{__('orgmgmt')['mails']['has_been']}} {{$data['action']}}.</p> 
<p>{{__('orgmgmt')['mails']['owners_note']}}:</p>                           
<p><i>{{$data['note']}}<i></p> 
 
{{__('orgmgmt')['mails']['thanks']}},<br>
<p>{{__('orgmgmt')['mails']['team']}} {{$data['name']}}</p>
@endcomponent