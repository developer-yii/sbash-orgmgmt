@extends('layouts.app')

@section('content')
<section class="content-header">
  <div class="container-fluid">
  <div class="row mb-2">
    <div class="col-sm-6">
    <h1>{{ __('orgmgmt')['header']['org_members'] }}</h1>
    </div>
    <div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
      <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="fa fa-home"></i></a></li>
      <li class="breadcrumb-item">{{ __('orgmgmt')['header']['organization'] }}</li>
      <li class="breadcrumb-item active">{{ __('orgmgmt')['header']['org_members'] }}</li>
    </ol>
    </div>
  </div>  
  </div><!-- /.container-fluid -->
</section>
<button type="button" class="" style="display:none;"  id="btn-add" data-toggle="modal" data-target="#myModal"><i class="fa fa-plus-square"></i> Add User</button>
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
                  <th>Name</th>
                  <th>Email</th>
                  <th>{{ __('orgmgmt')['table']['member_type'] }}</th>
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

<div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title mt-0" id="myModalLabel">Add User</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="#" method="post" id="forms"> 
        @csrf
        <input type="hidden" name="id_edit" id="id_edit" />
        <div class="modal-body">
          <div class="form-group">
            <label>{{ __('orgmgmt')['table']['member_type'] }}</label>
            <select class="form-control" name="member_type" id="member_type">
              <option value="1">{{ __('orgmgmt')['form']['owner'] }}</option>
              <option value="3">{{ __('orgmgmt')['form']['admin'] }}</option>              
              <option value="2">{{ __('orgmgmt')['form']['member'] }}</option>              
              <option value="4">{{ __('orgmgmt')['form']['contributor'] }}</option>
            </select>
            <span class="error"></span>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" id="btn-close" class="btn btn-secondary waves-effect"
            data-dismiss="modal">{{ __('orgmgmt')['form']['close'] }}</button>
          <button type="submit" class="btn btn-primary waves-effect waves-light">{{ __('orgmgmt')['form']['save'] }}</button>
        </div>
      </form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

@endsection

@section('app-js')
<script>
  var memberListUrl = "{{ route('organization.members.list')}}";
  var changeMemTypeUrl = "{{ route('organization.changeMemberType') }}";  
  var btnChange = "{{ __('orgmgmt')['form']['change'] }}";
  var btnProcessing = "{{ __('orgmgmt')['form']['processing'] }}";
  var lang = {!! $lang !!}
  var removeMemberUrl = "{{ route('organization.member.remove')}}";
  var alert1 = "{{ __('orgmgmt')['alert']['are_you_sure'] }}";
  var alertsub = "{{ __('orgmgmt')['alert']['are_you_sure_sub'] }}";
  var cancelText = "{{ __('orgmgmt')['form']['cancel'] }}";
  var confirmText = "{{ __('orgmgmt')['alert']['confirm_btn'] }}";
  var ErrorText = "{{ __('orgmgmt')['form']['error'] }}";
</script>

<script type="text/javascript" src="{{asset('/vendor/orgmgmt/js/organization-members.js')}}"></script>

@endsection