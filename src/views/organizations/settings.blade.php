@extends('layouts.app')

@section('app-css')  

  <link rel="stylesheet" type="text/css" href="{{asset('vendor/orgmgmt/pages/oraganization-settings.css')}}?{{time()}}">

@endsection

@section('content')
<section class="content-header">
  <div class="container-fluid">
  <div class="row mb-2">
    <div class="col-sm-6">
    <h1>{{ __('orgmgmt::organization.header.org_setting') }}</h1>
    </div>
    <div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
      <li class="breadcrumb-item"><a href="{{ url('administrator/dashboard') }}"><i class="fa fa-home"></i></a></li>
      <li class="breadcrumb-item active">{{ __('orgmgmt::organization.header.organization') }}</li>
      <li class="breadcrumb-item active">{{ __('orgmgmt::organization.header.settings') }}</li>
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
          <!-- /.box-header -->
          <div class="card-body">
            <form id="setting_form">
            @csrf
            <div class="form-group">
              <div class="row g-3 align-items-center">
                <div class="col-2">
                  <label for="inputPassword6" class="col-form-label">{{ __('orgmgmt::organization.form.organization_name') }}</label>
                </div>
                <div class="col-4">
                  <input type="text" class="form-control" id="name" name="name" placeholder="{{ __('orgmgmt::organization.form.enter_organization_name') }}" value="{{ ($org)?$org->name:"" }}">
                  <span class="error"></span>
                </div>              
              </div>
            </div>
            <div class="form-group">
              <div class="row g-3 align-items-center">
                <div class="col-2">
                  <label for="inputPassword6" class="col-form-label">{{ __('orgmgmt::organization.form.short_name') }}</label>
                </div>
                <div class="col-4">
                  <input type="text" class="form-control" id="short_name" name="short_name" placeholder="{{ __('orgmgmt::organization.form.enter_short_name') }}" value="{{ ($org)?$org->short_name:"" }}">
                  <span class="error short_name_error"></span>                
                </div>              
                <div class="col-3">
                  <div class="custom-control custom-checkbox">
                    <input class="custom-control-input custom-control-input-success" type="checkbox" id="short_name_available" name="short_name_available" checked="" readonly onclick="return false;" onkeydown="e = e || window.event; if(e.keyCode !== 9) return false;">
                    <label for="short_name_available" class="custom-control-label">{{ __('orgmgmt::organization.form.short_name_available') }}</label>
                  </div>
                  {{-- <div class="form-check">
                    @if($org)
                      <input class="form-check-input" type="checkbox" {{ ($org->short_name_available)?"checked":""}} name="short_name_available" id="short_name_available">
                    @else
                      <input class="form-check-input" type="checkbox" name="short_name_available" id="short_name_available">
                    @endif
                    <label class="form-check-label" for="short_name_available">
                      {{ __('organization.form.short_name_available')}}
                    </label>
                  </div> --}}
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="row g-3 align-items-center">
                <div class="col-2">
                  
                </div>
                <div class="col-6">
                  <div class="row">
                    <div class="col-3">
                      {{ __('orgmgmt::organization.form.public_pages') }}
                    </div>
                    <div class="col-auto">
                      @php
                      $url = '';
                        if(isset($org->short_name))
                        {
                          $url = url("")."/events/".$org->short_name; 
                        }
                      @endphp
                      <span id="public_page">{{ $url }}</span>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-3">
                      {{ __('orgmgmt::organization.form.email') }}
                    </div>
                    <div class="col-auto">
                      <span id="email">{{ ($org)?($org->short_name."@sbash.io"):""}}</span>
                    </div>
                  </div>
                </div>              
                <div class="col-3">                  
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="row g-3 align-items-center">
                <div class="col-2">
                  <label for="email_forward" class="col-form-label">{{ __('orgmgmt::organization.form.email_forward') }}</label>
                </div>
                <div class="col-4">
                  <input type="text" class="form-control" id="email_forward" name="email_forward" placeholder="{{ __('orgmgmt::organization.form.email_forward') }}" value="{{ ($org)?$org->email_forward:"" }}">
                  <span class="error"></span>
                </div>              
              </div>
            </div>
            <div class="form-group">
              <div class="row g-3 align-items-center">
                <div class="col-2">
                  <label for="double_optin" class="">{{ __('orgmgmt::organization.form.double_optin') }}
                  </label>
                </div>
                <div class="col-4"> 
                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input custom-control-input-success" id="double_optin" name="double_optin" {{ $org && $org->double_optin ? 'checked' : '' }}>
                    <label for="double_optin" class="custom-control-label"></label>
                  </div>
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="row g-3 align-items-center">
                <div class="col-2">
                  <label for="logo" class="col-form-label">{{ __('orgmgmt::organization.form.upload_logo') }}</label>
                </div>
                <div class="col-4">
                  <input type="file" id="logo" name="logo">
                </div>              
              </div>
              <div class="row">
                <div class="col-2"></div>
                <div class="col-4">     
                  <div id="img-prv">
                    
                  </div>
                  <div class="upload_file" id="uploadImages">
                    @if(isset($org->logo) && $org->logo)
                    <img id="preview-image" src="{{ asset('img/uploads/org_logo')."/".$org->logo}}" alt="preview image" style="max-height: 100px;">
                    @endif
                  </div>             
                  <span class="error"></span>
                </div>
              </div>
            </div>
            <div class="form-group">
              <div class="row">
                <div class="col-2"></div>
                <div class="col-4 text-right">
                  <a href="{{ route('home')}}" type="button" class="btn btn-secondary cancel-btn">{{ __('orgmgmt::organization.form.cancel') }}</a>
                  <button type="submit" class="btn btn-success">{{ __('orgmgmt::organization.form.save') }}</button>
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
  var addUpdateUrl = "{{ route('organization.addUpdate')}}";
  var short_name_error = "{{ __('orgmgmt::organization.form.short_name_not_available') }}";
  var successMsg = "{{ __('orgmgmt::organization.form.success') }}";
  var errIcon = "{{ __('orgmgmt::organization.form.error') }}";
  var checkNameUrl = "{{ route('organization.checkName')}}"
</script>

<script type="text/javascript" src="{{asset('/vendor/orgmgmt/js/organization-settings.js')}}"></script>
@endsection