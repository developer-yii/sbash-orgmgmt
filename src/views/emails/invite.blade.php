@component('mail::message')
# Hi,

You are invited to Join {{ $data['organization_name'] }} by {{ $data['user_name'] }}.

Kindly take action

<div style="display: inline-block">
@component('mail::button', ['url' => $data['urlApprove'], 'color' => 'success'])
Approve
@endcomponent
</div>

<div style="display: inline-block; margin-left: 10px">
@component('mail::button', ['url' => $data['urlReject'], 'color' => 'error'])
Reject
@endcomponent
</div>

Thanks,<br>
Team {{$data['organization_name']}}
@endcomponent
