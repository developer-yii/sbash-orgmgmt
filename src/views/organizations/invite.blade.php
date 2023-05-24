@extends('layouts.app')

@section('app-css')  

  <link rel="stylesheet" type="text/css" href="{{asset('vendor/orgmgmt/pages/oraganization-settings.css')}}?{{time()}}">

@endsection

@section('content')
<section class="content-header">
  <div class="container-fluid">
  <div class="row mb-2">
    <div class="col-sm-6">
    <h1>{{ __('orgmgmt::organization.header.invite_org') }}</h1>
    </div>
    <div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
      <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="fa fa-home"></i></a></li>
      <li class="breadcrumb-item active">{{ __('orgmgmt::organization.header.organization') }}</li>
      <li class="breadcrumb-item active">{{ __('orgmgmt::organization.header.invite_org') }}</li>
    </ol>
    </div>
  </div>
  <div class="row mb-2">
    <div class="col-sm-12 right-title">
      <div class="dropdown text-right content-right">
        {{-- <button type="button" class="btn btn-block btn-success btn-sm " id="btn-tambah" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus-square"></i> {{ __('eventelementtype.add_event_element_type') }}</button>       --}}
      </div>
    </div>
  </div>
  </div><!-- /.container-fluid -->
</section>

<section class="content">    
  <div class="row">
    <div class="col-md-12">
      <div class="card">
        <div class="card-body">
          <form id="invite_form">
            @csrf
            <div class="form-group">
              <div class="row g-3 align-items-center">                
                <div class="col-6">
                  <label for="invite_message">{{__('orgmgmt::organization.form.email')}}</label>
                  <input type="email" class="form-control" id="email" name="email" placeholder="{{ __('orgmgmt::organization.form.enter_email') }}" />
                  <span class="error"></span>
                </div>              
              </div>
            </div>
            <div class="form-group">
              <div class="row g-3 align-items-center">                
                <div class="col-6">
                  <label for="invite_message">{{__('orgmgmt::organization.form.invite_note')}}</label>
                  <textarea class="form-control" id="invite_message" name="invite_message"/></textarea>
                  <span class="error"></span>
                </div>              
              </div>
            </div>
            <div class="form-group">
              <div class="row">                
                <div class="col-6 text-right">                  
                  <button type="submit" class="btn btn-success">{{ __('orgmgmt::organization.form.invite') }}</button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

@endsection

@section('app-js')
<script>
  var inviteUrl = "{{ route('organization.send.invite')}}";
  var successMsg = "{{ __('orgmgmt::organization.form.success') }}";
  var errIcon = "{{ __('orgmgmt::organization.form.error') }}";  
  var inviteText = "{{ __('orgmgmt::organization.form.invite') }}";
  var invitingText = "{{ __('orgmgmt::organization.form.inviting') }}";
</script>

<script type="text/javascript" src="{{asset('/vendor/orgmgmt/js/organization-invite.js')}}"></script>
@endsection