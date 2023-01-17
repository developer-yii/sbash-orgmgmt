<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>SBash | SFlow</title>

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
  @if($joinSuccess && !$exists)
    <div class="lockscreen-name"><h2>You have successfully joined Organization</h2></div>
    <div class="lockscreen-name">{{ $email.' joined '.$org }}</div>
  @elseif($joinSuccess && !$exists)
    <div class="lockscreen-name"><h2>Organization join failed</h2></div>
  @else
    <div class="lockscreen-name"><h2>Already Member of Organization</h2></div>
    <div class="lockscreen-name">{{$email}} is already member of {{ $org}}</div>
  @endif

  <!-- START LOCK SCREEN ITEM -->
  <div class="lockscreen-item">
    <!-- lockscreen image -->
    
    <!-- /.lockscreen credentials -->

  </div>
  <!-- /.lockscreen-item -->
  <div class="help-block text-center">
    Login to SFlow, click below link
  </div>
  <div class="text-center">
    <a href="{{ route('login')}}">Sign in</a>
  </div>
  <div class="lockscreen-footer text-center">
    Copyright &copy; 2022-{{ date('Y')}} <b><a href="https://sbash.io" class="text-black">SBash.io</a></b><br>
    All rights reserved
  </div>
</div>
<!-- /.center -->

<!-- jQuery -->
<script src="../../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
