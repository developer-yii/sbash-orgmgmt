@extends('layouts.app')

@section('content')
<section class="content-header">
  <div class="container-fluid">
  <div class="row mb-2">
    <div class="col-sm-6">
    <h1>{{ __('orgmgmt')['header']['org_list'] }}</h1>
    </div>
    <div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
      <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="fa fa-home"></i></a></li>
      <li class="breadcrumb-item active">{{ __('orgmgmt')['header']['organization'] }}</li>
      <li class="breadcrumb-item active">{{ __('orgmgmt')['header']['org_list'] }}</li>
    </ol>
    </div>
  </div>  
  <div class="row mb-2">
    <div class="col-sm-12 right-title">
      <div class="dropdown content-right">        
          <button type="button" class="btn btn-block btn-success btn-sm " @if($createButtonDisabled) {{'disabled'}} @endif id="btn-tambah" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus-square"></i> {{ __('orgmgmt')['page']['add_organization'] }}</button>        
        </div>
    </div>
  </div>
  </div><!-- /.container-fluid -->
</section>
<section class="content">
    <div class="row">
    <div class="col-12">
      <div class="card">
        <!-- /.box-header -->
        <div class="card-body">

          <div class="table-responsive">

            <table id="datatable" class="table table-bordered table-hover display nowrap margin-top-10 w-p100">
              <thead>
                <tr>
                  <th>{{ __('orgmgmt')['table']['name'] }}</th>
                  <th>{{ __('orgmgmt')['form']['short_name'] }}</th>
                  <th>{{ __('orgmgmt')['form']['email'] }}</th>
                  <th>{{ __('orgmgmt')['form']['email_forward'] }}</th>    
                  <th>{{ __('orgmgmt')['table']['access_type'] }}</th>                             
                  <th>{{ __('orgmgmt')['table']['created'] }}</th>
                  <th>{{ __('orgmgmt')['table']['action'] }}</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
        <!-- /.box-body -->
      </div>
    </div>
  </div>
</section>

{{-- add organization modal --}}
<div id="myModal" class="modal fade" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title mt-0" id="myModalLabel">{{ __('orgmgmt')['page']['add_organization'] }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ route('organization.addUpdate')}}" method="post" id="setting_form"> 
        @csrf
        <input type="hidden" id="edit_id" name="id" value="">
        <div class="modal-body">
          <div class="form-group">
            <div class="row g-3 align-items-center">
                <div class="col-2">
                  <label for="inputPassword6" class="col-form-label">{{ __('orgmgmt')['form']['organization_name'] }}</label>
                </div>
                <div class="col-4">
                  <input type="text" class="form-control" id="name" name="name" placeholder="{{ __('orgmgmt')['form']['enter_organization_name'] }}" value="">
                  <span class="error"></span>
                </div>              
              </div>
          </div>
          <div class="form-group">
            <div class="row g-3 align-items-center">
              <div class="col-2">
                <label for="inputPassword6" class="col-form-label">{{ __('orgmgmt')['form']['short_name'] }}</label>
              </div>
              <div class="col-4">
                <input type="text" class="form-control" id="short_name" name="short_name" placeholder="{{ __('orgmgmt')['form']['enter_short_name'] }}" value="">
                <span class="error short_name_error"></span>                
              </div>              
              <div class="col-3">
                <div class="custom-control custom-checkbox">
                  <input class="custom-control-input custom-control-input-success" type="checkbox" id="short_name_available" name="short_name_available" checked="" readonly onclick="return false;" onkeydown="e = e || window.event; if(e.keyCode !== 9) return false;">
                  <label for="short_name_available" class="custom-control-label">{{ __('orgmgmt')['form']['short_name_available'] }}</label>
                </div>                
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="row g-3 align-items-center">
              <div class="col-2">
                
              </div>
              <div class="col-6">
                <div class="row">
                  <div class="col-4">
                    <b>{{ __('orgmgmt')['form']['public_pages'] }}:</b>
                  </div>
                  <div class="col-8">
                    @php
                    $url = '';
                      if(isset($org->short_name))
                      {
                        $url = url("")."/events/".$org->short_name; 
                      }
                    @endphp
                    <span id="public_page"></span>
                  </div>
                </div>
                <div class="row">
                  <div class="col-4">
                    <b>{{ __('orgmgmt')['form']['email'] }}:</b>
                  </div>
                  <div class="col-8">
                    <span id="email"></span>
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
                <label for="double_optin" class="">{{ __('orgmgmt')['form']['double_optin'] }}
                </label>
              </div>
              <div class="col-4"> 
                <div class="custom-control custom-checkbox">
                  <input type="checkbox" class="custom-control-input custom-control-input-success" id="double_optin" name="double_optin">
                  <label for="double_optin" class="custom-control-label"></label>
                </div>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="row g-3 align-items-center">
              <div class="col-2">
                <label for="email_forward" class="col-form-label">{{ __('orgmgmt')['form']['email_forward'] }}</label>
              </div>
              <div class="col-4">
                <input type="text" class="form-control" id="email_forward" name="email_forward" placeholder="{{ __('orgmgmt')['form']['email_forward'] }}" value="">
                <span class="error"></span>
              </div>              
            </div>
          </div>
          <div class="form-group">
            <div class="row g-3 align-items-center">
              <div class="col-2">
                <label for="default_footer" class="col-form-label">{{ __('orgmgmt')['form']['default_footer'] }}</label>
              </div>
              <div class="col-8">
                <textarea class="form-control ckeditor" id="default_footer" name="default_footer" placeholder="{{ __('orgmgmt')['form']['default_footer'] }}"></textarea>
                <span class="error"></span>
              </div>              
            </div>
          </div>
          <div class="form-group">
            <div class="row g-3 align-items-center">
              <div class="col-2">
                <label for="description" class="col-form-label">{{ __('orgmgmt')['form']['description'] }}</label>
              </div>
              <div class="col-8">
                <textarea class="form-control ckeditor" id="description" name="description" placeholder="{{ __('orgmgmt')['form']['description'] }}"></textarea>
                <span class="error"></span>
              </div>              
            </div>
          </div>
          <div class="form-group">
            <div class="row g-3 align-items-center">
              <div class="col-2">
                <label for="organizationinfo" class="" title="{{ __('orgmgmt')['title']['organizationinfo']}}">{{ __('orgmgmt')['form']['organizationinfo'] }}
                </label>
              </div>
              <div class="col-4"> 
                <div class="custom-control custom-checkbox">
                  <input type="checkbox" class="custom-control-input custom-control-input-success" id="organizationinfo" name="organizationinfo">
                  <label for="organizationinfo" class="custom-control-label"></label>
                </div>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="row g-3 align-items-center">
              <div class="col-2">
                <label for="logo" class="col-form-label">{{ __('orgmgmt')['form']['upload_logo'] }}</label>
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
                  
                  {{-- <img id="preview-image" src="" alt="preview image" style="max-height: 100px;"> --}}
                  
                </div>             
                <span class="error"></span>
              </div>
            </div>
          </div>            
        </div>
        <div class="modal-footer">
          <button type="button" id="btn-close" class="btn btn-secondary waves-effect"
            data-dismiss="modal">{{ __('orgmgmt')['form']['close'] }}</button>
          <button type="submit" class="btn btn-success">{{ __('orgmgmt')['form']['save'] }}</button>
        </div>
      </form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

@endsection

@section('app-js')
<script>
  var organizationGetUrl = "{{ route('organizations.getmylist') }}";        
  var detailUrl = "{{ route('organization.details')}}";
  var checkNameUrl = "{{ route('organization.checkName')}}"
  var addUpdateUrl = "{{ route('organization.addUpdate')}}";
  var short_name_error = "{{ __('orgmgmt')['form']['short_name_not_available'] }}";
  var select_image_file = "{{ __('orgmgmt')['validation']['select_image_file'] }}";
  var successMsg = "{{ __('orgmgmt')['form']['success'] }}";
  var errIcon = "{{ __('orgmgmt')['form']['error'] }}";
  var addOrgLang = "{{ __('orgmgmt')['page']['add_organization'] }}";
  var editOrgLang = "{{ __('orgmgmt')['page']['edit_organization'] }}";
  var chooseFileLang = "{{ __('orgmgmt')['choose_file'] }}";
  var checkNameUrl = "{{ route('organization.checkName')}}";
  var logoUrl = "{{ asset('img/uploads/org_logo')}}";
  var url = "{{url('')}}";
  var lang = {!! $lang !!}
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js" crossorigin="anonymous"></script>

<script type="text/javascript" src="{{asset('/vendor/orgmgmt/js/my-organizations.js')}}"></script>

@endsection