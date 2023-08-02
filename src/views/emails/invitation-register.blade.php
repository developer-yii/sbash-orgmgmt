@component('mail::message')
# Hello,

You have been invited to join {{ $data['organization_name'] }} by {{ $data['user_name'] }}. 

<p>following is inviters note:</p>                           
<p><i>{{$data['invite_message']}}<i></p> 

To join, please register on our system using the button below:

@component('mail::button', ['url' => route('register').'?email='.$data['email']])
Register
@endcomponent

If you have any questions, please contact us at {{$data['organization_email']}}.

Thanks,<br>
Team {{$data['organization_name']}}.
@endcomponent
