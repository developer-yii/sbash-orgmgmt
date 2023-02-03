@extends('layouts.app')

@section('content')
<section class="content-header">
  <div class="container-fluid">
  <div class="row mb-2">
    <div class="col-sm-6">
    <h1>{{ __('orgmgmt::organization.header.org_list') }}</h1>
    </div>
    <div class="col-sm-6">
    <ol class="breadcrumb float-sm-right">
      <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="fa fa-home"></i></a></li>
      <li class="breadcrumb-item active">{{ __('orgmgmt::organization.header.organization') }}</li>
      <li class="breadcrumb-item active">{{ __('orgmgmt::organization.header.org_list') }}</li>
    </ol>
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
                  <th>Name</th>
                  <th>{{ __('orgmgmt::organization.form.short_name') }}</th>
                  <th>Email</th>
                  <th>{{ __('orgmgmt::organization.form.email_forward') }}</th>
                  <th>{{ __('orgmgmt::organization.table.user') }}</th>                  
                  <th>{{ __('orgmgmt::organization.table.created') }}</th>
                  <th>{{ __('orgmgmt::organization.table.action') }}</th>
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

{{-- invite modal --}}
<div id="myModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title mt-0" id="myModalLabel">Add User</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ route('organization.send.invite')}}" method="post" id="forms"> 
        @csrf
        <input type="hidden" name="id_edit" id="id_edit" />
        <div class="modal-body">
          <div class="form-group">
            <div class="row g-3 align-items-center">                
              <div class="col-md-12">
                <input type="email" class="form-control" id="email" name="email" placeholder="{{ __('orgmgmt::organization.form.enter_email') }}" />
                <span class="error"></span>
              </div>              
            </div>
          </div>            
        </div>
        <div class="modal-footer">
          <button type="button" id="btn-close" class="btn btn-secondary waves-effect"
            data-dismiss="modal">{{ __('orgmgmt::organization.form.close') }}</button>
          <button type="submit" class="btn btn-success">{{ __('orgmgmt::organization.form.invite') }}</button>
        </div>
      </form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

@endsection

@section('app-js')
<script>
  var organizationGetUrl = "{{ route('organizations.get') }}";    
  var inviteText = "{{ __('orgmgmt::organization.form.invite') }}";
  var invitingText = "{{ __('orgmgmt::organization.form.inviting') }}";
  var inviteUrl = "{{ route('organization.send.invite')}}";
  var lang = {!! $lang !!}
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js" crossorigin="anonymous"></script>

<script type="text/javascript" src="{{asset('/vendor/orgmgmt/js/organization-list.js')}}"></script>

@endsection