<?php
namespace Sbash\Orgmgmt\Controllers;

use Illuminate\Http\Request;
use Sbash\Orgmgmt\Controllers\Controller;
use Sbash\Orgmgmt\Models\Organization;
use Sbash\Orgmgmt\Models\OrgInvitationLog;
use Sbash\Orgmgmt\Models\UserOrganization;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Sbash\Orgmgmt\Mail\InviteMail;
use Sbash\Orgmgmt\Mail\InvitationActionMail;
use Sbash\Orgmgmt\Mail\InviteNonRegisteredMail;
use Sbash\Orgmgmt\Models\InvitedUser;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use DataTables;
use DB;

class OrganizationController extends Controller
{
  public function settings(Request $request)
  {
      if (!auth()->user()->can('organization_settings_view')) {
        return redirect()->back()->with(['flash_message_error' => trans('orgmgmt::organization.notification.no_view_set_perm')]);
      }

      $org = Organization::where('user_id',\Auth::user()->id)->where('deleted_at',null)->first();

      return view('orgmgmt::organizations.settings',compact('org'));
  }

  public function addUpdate(Request $request)
  {

      $rules = [
          'name' => 'required|max:100',
          'short_name_available' => 'sometimes',
          'short_name' => 'required_with:short_name_available,on',
          'email_forward' => 'required|email',
          'logo' => 'mimes:jpeg,jpg,png,gif|sometimes|max:2000'                      
      ];

      $validation = Validator::make($request->all(), $rules);

      if ($validation->fails()) {      
          $result = ['status' => false, 'message' => $validation->errors(), 'data' => []];
          return response()->json($result);
      }
      else
      {
        if($request->id)
        {
          $org = Organization::find($request->id);          

          $image_name = $org->logo;

          if ($request->hasFile('logo')) {

              if($org->logo)
              {
                $path = public_path('img/uploads/org_logo/'.$org->logo);
                if (file_exists($path)) {
                  unlink($path);
                }
              }

              $image = $request->file('logo');
              $image_name = time().rand(1000,9999).'.'.$image->getClientOriginalExtension();
              $destinationPath = public_path('img/uploads/org_logo');
              $image->move($destinationPath, $image_name);
          }

          $email = trim($request->short_name);                
          $email = $email.'@sbash.io';
          $uId = \Auth::user()->id;

          $r = $org->update([
            'name' => $request->name,
            'short_name' => $request->short_name,
            'email' => $email,              
            'email_forward' => $request->email_forward,              
            'short_name_available' => ($request->short_name_available) ? 1 : 0,   
            'double_optin' => ($request->double_optin) ? 1 : 0,     
            'default_footer' => $request->default_footer,      
            'logo' => $image_name,
            'updated_by' => $uId,
          ]);

          if($r)
          {
              $result = ['status' => true, 'message' =>trans('orgmgmt::organization.notification.org_add_success')];
              return response()->json($result);
          }
          else
          {
              $result = ['status' => false, 'message' =>trans('orgmgmt::organization.notification.org_add_fail')];
              return response()->json($result);
          }
        }

          $orgs = Organization::where('user_id',\Auth::user()->id)->where('deleted_at',null)->get();

          if(count($orgs) >= 2)
          {
            // return redirect()->back()->with(['flash_message_error' => trans('orgmgmt::organization.notification.already_two_org_created')]);
            return response()->json(['message' => trans('orgmgmt::organization.notification.already_two_org_created')], 422);
          }

          $image_name = '';
          if ($request->hasFile('logo')) {
              $image = $request->file('logo');
              $image_name = time().rand(1000,9999).'.'.$image->getClientOriginalExtension();
              $destinationPath = public_path('img/uploads/org_logo');
              $image->move($destinationPath, $image_name);
          }

          $email = trim($request->short_name);                
          $email = $email.'@sbash.io';

          $r = Organization::create([
            'name' => $request->name,
            'short_name' => $request->short_name,
            'email' => $email,
            'email_forward' => $request->email_forward,              
            'short_name_available' => ($request->short_name_available) ? 1 : 0,
            'double_optin' => ($request->double_optin) ? 1 : 0,
            'default_footer' => $request->default_footer,
            'logo' => $image_name,
            'user_id' => \Auth::user()->id,      
          ]);

          $userOrg = new UserOrganization;
          $userOrg->user_id = \Auth::user()->id;
          $userOrg->organization_id = $r->id;
          $userOrg->user_type = 'users';
          $userOrg->access_type = 1; // 1 for owner, 2 for member
          $userOrg->save();         

          // else{

          //     $image_name = $org->logo;

          //     if ($request->hasFile('logo')) {
          //         $image = $request->file('logo');
          //         $image_name = time().rand(1000,9999).'.'.$image->getClientOriginalExtension();
          //         $destinationPath = public_path('img/uploads/org_logo');
          //         $image->move($destinationPath, $image_name);
          //     }

          //     $email = trim($request->short_name);                
          //     $email = $email.'@sbash.io';

          //     $r = $org->update([
          //       'name' => $request->name,
          //       'short_name' => $request->short_name,
          //       'email' => $email,              
          //       'email_forward' => $request->email_forward,              
          //       'short_name_available' => ($request->short_name_available) ? 1 : 0,
          //       'double_optin' => ($request->double_optin) ? 1 : 0,              
          //       'logo' => $image_name,
          //       'user_id' => \Auth::user()->id,      
          //     ]);
          //     $uId = \Auth::user()->id;

          //     if($r)
          //     {
          //       if(!session()->has('organization_id') && !session('organization_id'))
          //       {
          //         session(['organization_id' => $r->id]);
          //       }
                
          //       $userOrg = UserOrganization::where('user_id',$uId)->where('organization_id',$org->id)->first();
          //       if(!$userOrg)
          //       {
          //         $userOrg = new UserOrganization;
          //         $userOrg->user_id = \Auth::user()->id;
          //         $userOrg->organization_id = $org->id;
          //         $userOrg->user_type = 'users';
          //         $userOrg->access_type = 1; // 1 for owner, 2 for member
          //         $userOrg->save();
          //       }
          //     }
          // }

          if($r)
          {
              $result = ['status' => true, 'message' =>trans('orgmgmt::organization.notification.org_add_success')];
              return response()->json($result);
          }
          else
          {
              $result = ['status' => false, 'message' =>trans('orgmgmt::organization.notification.org_add_fail')];
              return response()->json($result);
          }
      }
  }
  public function checkName(Request $request)
  {
    if($request->name)
    {
        $org = Organization::where('short_name',$request->name)->where('deleted_at',null)->first();

        if($org)
        {
            if(\Auth::user()->id == $org->user_id)
            {
                $result = ['status' => true, 'available' => true];
                return response()->json($result);   
            }

            $result = ['status' => true, 'available' => false];
            return response()->json($result);
        }
        else
        {
            $result = ['status' => true, 'available' => true];
            return response()->json($result);
        }
    }
    else
    {
        $result = ['status' => false, 'available' => ''];
        return response()->json($result);
    }
  }

  public function invite(Request $request)
  {
    if (!auth()->user()->can('invite_to_organization')) {
      return redirect()->back()->with(['flash_message_error' => trans('orgmgmt::organization.notification.no_invite_org_perm')]);
    }

    if(!auth()->user()->isOwnerOfOrganization())
    {
      return redirect()->back()->with(['flash_message_error' => trans('usermgmt::notification.update_org_settings')]);
    }    

    return view('orgmgmt::organizations.invite');
  }

  public function sendInvite(Request $request)
  {
    if (!auth()->user()->can('invite_to_organization')) {      
      return response()->json(['message' => trans('orgmgmt::organization.notification.no_invite_org_perm')], 422);
    }

    $rules = [
      'email' => 'required|email',
    ];

    // $messages = [
    //   'email.exists' => trans('orgmgmt::organization.validation.email_not_registered'),
    // ];

    $validation = Validator::make($request->all(), $rules);

    if ($validation->fails()) {      
        $result = ['status' => false, 'message' => $validation->errors(), 'data' => []];
        return response()->json($result);
    }
    else
    {

      $user = \Auth::user();

      if(isset($request->id_edit) && $request->id_edit)
      {
        $org = Organization::find($request->id_edit);
      }
      else
      {
        $org = Organization::where('user_id',$user->id)->where('deleted_at',null)->first();
      }

      // Check if invited email is registered or not
      $existingUser = User::where('email',$request->email)->first();

      $orgObj = Organization::find(session('organization_id'));

      $oId = $orgObj->id ?? $org->id;

      $existingInvite = InvitedUser::where('email',$request->email)->where('organization_id',$oId)->where('is_registered',0)->first();      

      if(!$existingUser && !$existingInvite)
      {
        $invitedUsr = new InvitedUser;
        $invitedUsr->email = $request->email;
        $invitedUsr->organization_id = session('organization_id') ?? $org->id;        
        $invitedUsr->invited_by = $user->id;
        $res = $invitedUsr->save();

        // Email user a mail for invitation
        $toEmail = $request->email;
        $from = $user->email;
        $data = [            
            'organization_name' => $orgObj->name ?? $org->name,
            'organization_email' => $orgObj->email ?? $org->email,
            'user_name' => $user->name,            
        ];
        Mail::to($toEmail)->send(new InviteNonRegisteredMail($data,$from));      

        if($res)
        {          
          $result = ['status' => true, 'message' => trans('orgmgmt::organization.notification.invi_sent'), 'data' => []];
          return response()->json($result);
        }
        else{
          $result = ['status' => false, 'message' => 'Something went wrong', 'data' => []];
          return response()->json($result);   
        }
      }

      if($existingInvite)
      {        
        return response()->json(['message' => trans('orgmgmt::organization.validation.already_sent')], 422);       
      }

      if($org)
      {
        //validation for own email
        if(!isset($request->id_edit) && !$request->id_edit)
        {
          if($user->email == $request->email)
          {
            $validation->getMessageBag()->add('email', trans('orgmgmt::organization.validation.owner_org'));
            $result = ['status' => false, 'message' => $validation->errors(), 'data' => []];
            return response()->json($result); 
          }
        }

        $toUser = User::where('email',$request->email)->first();
        $existCheck = UserOrganization::where('user_id',$toUser->id)->where('organization_id',$org->id)->first();
        if($existCheck)
        {
          $validation->getMessageBag()->add('email', trans('orgmgmt::organization.validation.already_registered'));
          $result = ['status' => false, 'message' => $validation->errors(), 'data' => []];
          return response()->json($result);
        }

        $orgInvitation = new OrgInvitationLog;
        $orgInvitation->organization_id = $org->id;
        $orgInvitation->to_email = $request->email;
        $orgInvitation->invited_by = $user->id;
        $orgInvitation->save();

        // Email user a mail for invitation
        $toEmail = $request->email;
        $from = $user->email;
        $data = [
            'organization_id' => $org->id,
            'organization_name' => $org->name,
            'organization_email' => $org->email,
            'user_name' => $user->name,
            'urlApprove' => URL::temporarySignedRoute('invite-link', now()->addDays(5), ['org' => $org->short_name,'email' => $request->email, 'action' => 'approve']),
            'urlReject' => URL::temporarySignedRoute('invite-link', now()->addDays(5), ['org' => $org->short_name,'email' => $request->email, 'action' => 'reject']),
        ];
        Mail::to($toEmail)->send(new InviteMail($data,$from));        

        $result = ['status' => true, 'message' => trans('orgmgmt::organization.notification.invi_sent'), 'data' => []];
        return response()->json($result);
      }
      else{
        $validation->getMessageBag()->add('email', trans('orgmgmt::organization.validation.org_text_1'));
        $result = ['status' => false, 'message' => $validation->errors(), 'data' => []];
        return response()->json($result);   
      }
    }
  }

  public function joinOrganization(Request $request, $org, $email, $action)
  {
    if($email && $org)
    {
      $user = User::where('email',$email)->first();
      $organization = Organization::where('short_name',$org)->where('deleted_at',null)->first();

      $existCheck = UserOrganization::where('user_id',$user->id)->where('organization_id',$organization->id)->first();
      $exists = false;
      $joinSuccess = false;
      $alreadyAction = false;       

      if($existCheck)
      {
        $exists = true;
        $alreadyAction = true;
        return view('orgmgmt::organizations.organization-join',compact('email','org','joinSuccess','action','exists','alreadyAction'));
      }

      $invLogCheck = OrgInvitationLog::where('organization_id',$organization->id)
                          ->where('to_email',$email)->where('invitation_status','!=',0)->first();

      if($invLogCheck)
      {
        $alreadyAction = true;
        return view('orgmgmt::organizations.organization-join',compact('email','org','joinSuccess','action','exists','alreadyAction'));
      }

      $invLog = OrgInvitationLog::where('organization_id',$organization->id)
                          ->where('to_email',$email)
                          ->update(['invitation_status' => ($action == 'approve' ? 1 : 2)]);      

      if($action == 'approve')
      {        
        $userOrg = new UserOrganization;
        $userOrg->user_id = $user->id;
        $userOrg->organization_id = $organization->id;
        $userOrg->user_type = 'users';
        $userOrg->access_type = 2; // 1 for owner, 2 for member
        if($userOrg->save())
          $joinSuccess = true;
      }      

      $userOrgs = UserOrganization::where('organization_id',$organization->id)->where('access_type',1)->get();      

      if(count($userOrgs))
      {
          $orgObj = Organization::find($organization->id);
          
          foreach($userOrgs as $uorg)
          {
              $userObj = User::find($uorg->user_id);
              // Email organization owner for notification
              $from = $orgObj->email;              
              $data = [
                  'organization_name' => $orgObj->name,
                  'organization_email' => $orgObj->email,
                  'sender_name' => $user->name,
                  'user_name' => $userObj->name,
                  'action' => $action
              ];
              Mail::to($userObj->email)->send(new InvitationActionMail($data,$from));        
          }
      }

      if($joinSuccess)
      {        
        return view('orgmgmt::organizations.organization-join',compact('email','org','joinSuccess','exists','action','alreadyAction'));
      }
      else{
        return view('orgmgmt::organizations.organization-join',compact('email','org','joinSuccess','exists','action','alreadyAction')); 
      }
    }    
  }

  public function members(Request $request)
  {

    if (!auth()->user()->can('organization_member_list')) {
      return redirect()->back()->with(['flash_message_error' => trans('orgmgmt::organization.notification.no_member_view_perm')]);
    }

    $orgId = \Auth::user()->organization->id ?? '';

    if(!$orgId)
    {      
      return redirect()->back()->with(['flash_message_error' => trans('usermgmt::notification.update_org_settings')]);
    }

    $lang = $this->get_DataTable_LanguageBlock();
    return view('orgmgmt::organizations.members',compact('lang'));
  }

  public function get_DataTable_LanguageBlock()
  {
    $result = "
             {
              // url: '//cdn.datatables.net/plug-ins/1.10.19/i18n/English.json',
              // url: '//cdn.datatables.net/plug-ins/1.11.4/i18n/de_de.json'
              'sEmptyTable': '" . __('orgmgmt::event.table.empty') . "',
              'sInfo': '" . __('orgmgmt::event.table.info.sh') . " ' + '_START_ ' + '" . __('orgmgmt::event.table.info.to') . " ' +
                ' _END_' + ' " . __('orgmgmt::event.table.info.of') . " ' + '_TOTAL_' +
                ' " . __('orgmgmt::event.table.info.ent') . "',
              'sInfoEmpty': '" . __('orgmgmt::event.table.empty') . "',
              'sInfoFiltered': '(" . __('orgmgmt::event.table.filter.pre') . " _MAX_ " . __('orgmgmt::event.table.filter.post') . ")',
              'sInfoPostFix': '',
              'sInfoThousands': '" . __('orgmgmt::event.table.thousand_separator') . "',
              'sLengthMenu': '" . __('orgmgmt::event.table.info.length_a') . " ' + '_MENU_' +
                ' " . __('orgmgmt::event.table.info.length_b') . "',
              'sLoadingRecords': '" . __('orgmgmt::event.table.loading') . "',
              'sProcessing': '" . __('orgmgmt::event.table.processing') . "',
              'sSearch': '" . __('orgmgmt::event.table.sc') . "',
              'sZeroRecords': '" . __('orgmgmt::event.table.nr') . "',
              'oPaginate': {
                'sFirst': 'First',
                'sLast': 'Last',
                'sNext': '" . __('orgmgmt::event.table.paginate.next') . "',
                'sPrevious': '" . __('orgmgmt::event.table.paginate.prev') . "',
              },
              'oAria': {
                'sSortAscending': ': activate to sort column ascending',
                'sSortDescending': ': activate to sort column descending'
              }
            }";

    return $result;
  }

  public function getMembers(Request $request)
  {
    if(isset($request->name))
    {
      $org = Organization::where('short_name',$request->name)->first();
    }
    else
    {      
      $user = \Auth::user();
      $org = Organization::where('user_id',$user->id)->first();
    }

    $result = DB::table('user_organizations')
            ->leftJoin('users','user_organizations.user_id','=','users.id')
            ->leftJoin('organizations','user_organizations.organization_id','=','organizations.id')
            ->where('user_organizations.organization_id',session('organization_id'))           
            ->select('user_organizations.id','users.name','users.email','user_organizations.access_type',DB::raw('(CASE user_organizations.access_type WHEN 1 THEN "OWNER" WHEN 2 THEN "MEMBER" ELSE "" END) AS member_type'))
            ->orderBy('users.name','asc');

      if ($request->ajax()) {
        return DataTables::queryBuilder($result)          
          ->addColumn('actions', function ($data) {
             $button = '';
            if (auth()->user()->can('member_type_change')) {
              $button .= '<button class="btn btn-primary waves-effect waves-light edit" id="' . $data->id . '" data-toggle="tooltip" data-placement="right" title="Edit type" data-member="'.$data->access_type.'"><i class="fa fa-edit"></i></button>';           
            }
            return $button;
          })->rawColumns(['actions'])
          ->toJson();
      }
  }

  public function changeMemberType(Request $request)
  {
    if(!auth()->user()->can('member_type_change'))
    {
      return response()->json(['message' => trans('orgmgmt::organization.notification.no_member_type_change_perm')], 422);
    }
    
    $rules = [
      'member_type' => 'required',                              
    ];

    $messages = [
      'member_type.required' => trans('orgmgmt::organization.validation.sel_mem_type'),
    ];

    $validation = Validator::make($request->all(), $rules, $messages);

    if ($validation->fails()) {      
        $result = ['status' => false, 'message' => $validation->errors(), 'data' => []];
        return response()->json($result);
    }
    else
    {
      if($request->id_edit)
      {        
        $userOrg = UserOrganization::find($request->id_edit);
        if($userOrg)
        {         
          $org = Organization::find($userOrg->organization_id);          

          if($org->user_id == $userOrg->user_id)
          {
            $validation->getMessageBag()->add('member_type', trans('orgmgmt::organization.validation.org_val_1'));
            $result = ['status' => false, 'message' => $validation->errors(), 'data' => []];
            return response()->json($result);
          }

          $userOrg->access_type = $request->member_type;
          $userOrg->save();
          
          $result = ['status' => true, 'message' => trans('orgmgmt::organization.notification.mem_type_changed'), 'data' => []];
          return response()->json($result);
        }
      }
      $result = ['status' => false, 'message' => trans('orgmgmt::organization.notification.mem_type_change_fail'), 'data' => []];
      return response()->json($result);
    }
  }

  public function list(Request $request)
  {    
    if (!auth()->user()->can('organization_list')) {
      return redirect()->back()->with(['flash_message_error' => trans('orgmgmt::organization.notification.no_org_list_perm')]);
    }    

    $lang = $this->get_DataTable_LanguageBlock();
    return view('orgmgmt::organizations.list',compact('lang')); 
  }

  public function get(Request $request)
  {
    $results = Organization::with('user')          
            ->where('deleted_at',null)          
            ->get();

    return DataTables::of($results)        
      ->addColumn('user_name', function($data){          
          return $data->user->name ?? '';
      })  
      ->addColumn('actions', function ($data) {
        $editRoute = route('organization.edit').'?name='.$data->short_name;
        $listRoute = route('org.members.list').'?name='.$data->short_name;
        $button = '<a class="btn btn-primary waves-effect waves-light edit" target="_blank" href="' . $editRoute . '" data-toggle="tooltip" data-placement="right" title="Edit"><i class="fa fa-edit"></i></a>';
        $button .= '<a class="btn btn-success waves-effect waves-light ml-1" target="_blank" href="' . $listRoute . '" data-toggle="tooltip" data-placement="right" title="members"><i class="fa fa-users"></i></a>';
        if (auth()->user()->can('invite_to_organization')) {
          $button .= '<a class="btn btn-warning waves-effect waves-light ml-1 invite-btn" data-toggle="modal" data-target="#myModal" data-toggle="tooltip" data-id="'.$data->id.'" data-placement="right" title="Invite"><i class="fa fa-paper-plane"></i></a>';
        }            
        return $button;
      })->rawColumns(['actions'])
      ->toJson();

  }

  public function orgJoinList(Request $request)
  {
    $lang = $this->get_DataTable_LanguageBlock();
    return view('orgmgmt::organizations.organization-list',compact('lang')); 
  }

  public function getOrgs(Request $request)
  {
    $results = Organization::with('user')          
            ->where('deleted_at',null)          
            ->get();

    return DataTables::of($results)        
      ->addColumn('user_name', function($data){          
          return $data->user->name ?? '';
      })
      ->addColumn('organization_name', function ($data){
          return $data->name ?? '';
      })  
      ->addColumn('actions', function ($data) {       
        
          $button = '<a class="btn btn-warning waves-effect waves-light ml-1 request-btn" data-toggle="modal" data-target="#myModal" data-toggle="tooltip" data-id="'.$data->id.'" title="Request">Join</a>';
                    
        return $button;
      })->rawColumns(['actions'])
      ->toJson();

  }

  public function edit(Request $request)
  {
      if (!auth()->user()->can('organization_edit')) {
        return redirect()->back()->with(['flash_message_error' => trans('orgmgmt::organization.notification.no_org_edit_perm')]);
      }      

      if($request->name)
      {
        $org = Organization::where('short_name',$request->name)->where('deleted_at',null)->first();

        if($org)
        {
          return view('orgmgmt::organizations.edit',compact('org'));
        }

        return redirect()->back()->with(['flash_message_error' => trans('orgmgmt::organization.notification.no_org_found')]);
      }
      else
      {
        return redirect()->back()->with(['flash_message_error' => trans('orgmgmt::organization.form.error')]);
      }
  }

  public function editUpdate(Request $request)
  {
    if (!auth()->user()->can('organization_edit')) {
      return response()->json(['message' => trans('orgmgmt::organization.notification.no_org_edit_perm')], 422);
    }

    $rules = [
          'name' => 'required|max:100',
          'short_name_available' => 'sometimes',
          'short_name' => 'required_with:short_name_available,on',
          'email_forward' => 'required|email',
          'logo' => 'mimes:jpeg,jpg,png,gif|sometimes|max:2000',
      ];

      $validation = Validator::make($request->all(), $rules);

      if ($validation->fails()) {      
          $result = ['status' => false, 'message' => $validation->errors(), 'data' => []];
          return response()->json($result);
      }
      else
      {
          $org = Organization::find($request->id);          

          $image_name = $org->logo;

          if ($request->hasFile('logo')) {

              if($org->logo)
              {
                $path = public_path('img/uploads/org_logo/'.$org->logo);
                if (file_exists($path)) {
                  unlink($path);
                }
              }

              $image = $request->file('logo');
              $image_name = time().rand(1000,9999).'.'.$image->getClientOriginalExtension();
              $destinationPath = public_path('img/uploads/org_logo');
              $image->move($destinationPath, $image_name);
          }

          $email = trim($request->short_name);                
          $email = $email.'@sbash.io';
          $uId = \Auth::user()->id;

          $r = $org->update([
            'name' => $request->name,
            'short_name' => $request->short_name,
            'email' => $email,              
            'email_forward' => $request->email_forward,              
            'short_name_available' => ($request->short_name_available) ? 1 : 0,              
            'logo' => $image_name,
            'updated_by' => $uId,
            'double_optin' => ($request->double_optin) ? 1 : 0,
          ]);

          if($r)
          {
              $result = ['status' => true, 'message' =>trans('orgmgmt::organization.notification.org_add_success')];
              return response()->json($result);
          }
          else
          {
              $result = ['status' => false, 'message' =>trans('orgmgmt::organization.notification.org_add_fail')];
              return response()->json($result);
          }
      }
  }

  public function memberlist(Request $request)
  {
    if (!auth()->user()->can('members_list')) {
      return redirect()->back()->with(['flash_message_error' => trans('orgmgmt::organization.notification.no_mem_list_perm')]);
    }      

    if($request->name)
    {
      $org = Organization::where('short_name',$request->name)->where('deleted_at',null)->first();

      if($org)
      {
        $lang = $this->get_DataTable_LanguageBlock();
        return view('orgmgmt::organizations.member-list',compact('org','lang'));
      }

      return redirect()->back()->with(['flash_message_error' => trans('orgmgmt::organization.notification.no_org_found')]);
    }
    else
    {
      return redirect()->back()->with(['flash_message_error' => trans('orgmgmt::organization.form.error')]);
    }
  }

  public function mylist(Request $request)
  {    
    $lang = $this->get_DataTable_LanguageBlock();
    return view('orgmgmt::organizations.my-organizations',compact('lang')); 
  }

  public function getMyList(Request $request)
  {
    $results = Organization::whereHas('user', function ($query) {
                $query->where('id', auth()->user()->id);
            })
            ->get();

    return DataTables::of($results)                
      ->addColumn('actions', function ($data) {
        $button = '<a class="btn btn-primary waves-effect waves-light edit" data-id="'.$data->id.'" target="_blank" href="javascript:void(0)" title="Edit"><i class="fa fa-edit"></i></a>';                    
        return $button;
      })->rawColumns(['actions'])
      ->toJson();
  }

  public function details(Request $request)
  {
    if($request->id)
    {
      if(!auth()->user()->isOrganizationOwner($request->id))
      {
        return response()->json(['message' => trans('orgmgmt::organization.notification.no_org_edit_perm')], 422);
      }

      $model = Organization::find($request->id);

      $result = ['status' => true, 'message' => '', 'detail' => $model];
      return response()->json($result);

    }
    else{
      return response()->json(['message' => trans('orgmgmt::organization.notification.no_org_found')], 422);
    }

  }
}