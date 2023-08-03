<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ env('APP_NAME')}} </title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">
</head>
<body class="hold-transition lockscreen">
<!-- Automatic element centering -->
<div class="lockscreen-wrapper">
  <div class="lockscreen-logo">
    <a href="../../index2.html"><b>SBash</b>IO</a>
  </div>
  <!-- User name -->
  @if($joinSuccess && !$exists && $action == 'approve')
    <div class="lockscreen-name"><h2>{{ __('orgmgmt::organization.orgjoin.text-1') }}</h2></div>
    <div class="lockscreen-name">{{ $email.' joined '.$org }}</div>
  @elseif(!$exists && $alreadyAction)
    <div class="lockscreen-name"><h2>{{ __('orgmgmt::organization.orgjoin.text-7') }}</h2></div>
  @elseif(!$exists && !$joinSuccess && $action == 'reject')
    {{-- <div class="lockscreen-name"><h2>{{ __('orgmgmt::organization.orgjoin.text-2') }}</h2></div> --}}
    <div class="lockscreen-name"><h2>{{ __('orgmgmt::organization.orgjoin.text-6') }}</h2></div>
  @else
    <div class="lockscreen-name"><h2>{{ __('orgmgmt::organization.orgjoin.text-3') }}</h2></div>
    <div class="lockscreen-name">{{$email}} {{ __('orgmgmt::organization.orgjoin.text-4') }} {{ $org}}</div>
  @endif

  <!-- START LOCK SCREEN ITEM -->
  <div class="lockscreen-item">
    <!-- lockscreen image -->
    
    <!-- /.lockscreen credentials -->

  </div>
  <!-- /.lockscreen-item -->
  @if($action != 'reject' || $exists)
  <div class="help-block text-center">
    {{ __('orgmgmt::organization.orgjoin.text-5') }}
  </div>
  
  <div class="text-center">
    <a href="{{ route('login')}}">{{ __('orgmgmt::organization.form.signin') }}</a>
  </div>
  @endif
  <div class="lockscreen-footer text-center">
    {{ __('orgmgmt::organization.orgjoin.copyright') }} &copy; 2022-{{ date('Y')}} <b><a href="https://sbash.io" class="text-black">SBash.io</a></b><br>
    {{ __('orgmgmt::organization.orgjoin.right_reserved') }}
  </div>
</div>
<!-- /.center -->

<!-- jQuery -->
{{-- <script src="../../plugins/jquery/jquery.min.js"></script> --}}
<!-- Bootstrap 4 -->
{{-- <script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script> --}}
</body>
</html>
