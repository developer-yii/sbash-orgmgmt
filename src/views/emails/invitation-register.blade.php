@component('mail::message')
#Hello,

You have been invited to join {{ $data['organization_name'] }} by {{ $data['user_name'] }}. 

To join, please register on our system using the button below:

@component('mail::button', ['url' => route('register')])
Register
@endcomponent

If you have any questions, please contact us at {{$data['organization_email']}}.

Thanks,<br>
Team {{$data['organization_name']}}.
@endcomponent
