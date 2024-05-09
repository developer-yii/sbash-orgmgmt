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
use Sbash\Orgmgmt\Mail\InviteNonRegisteredUserMail;
use Sbash\Orgmgmt\Models\InvitedUser;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use DataTables;
use DB;

class OrganizationController extends Controller
{

  public function __construct()
  {
      if(class_exists('App\Http\Middleware\PreventBackHistory')){
        $this->middleware(['preventBackHistory']);
      }
  }

  public function settings(Request $request)
  {
      if (!auth()->user()->can('organization_settings_view')) {
        return redirect()->back()->with(['flash_message_error' => __('orgmgmt')['notification']['no_view_set_perm']]);
      }

      $org = Organization::where('user_id',\Auth::user()->id)->where('deleted_at',null)->first();

      return view('orgmgmt::organizations.settings',compact('org'));
  }

  public function addUpdate(Request $request)
  {

      $rules = [
          'name' => 'required|max:100',
          'short_name_available' => 'sometimes',
          'short_name' => 'required_with:short_name_available,on|max:50|string|alpha_num',
          'email_forward' => 'required|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
          'logo' => 'mimes:jpeg,jpg,png,gif|sometimes|max:2048'
      ];

      $messages = [
        'logo.max' => __('orgmgmt')['validation']['logo']['max'],
        'name.max' => __('orgmgmt')['validation']['max']['string'],
        'name.required' => __('orgmgmt')['validation']['required'],
        'short_name.max' => __('orgmgmt')['validation']['max']['string'],
        'short_name.required_with' => __('orgmgmt')['validation']['required_with'],
        'email_forward.required' => __('orgmgmt')['validation']['required'],
        'email_forward.regex' => __('orgmgmt')['validation']['regex'],
      ];

      $validation = Validator::make($request->all(), $rules, $messages);

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
            'show_organization_info' => ($request->organizationinfo) ? true : false,
            'description' => $request->description,
            'default_footer' => $request->default_footer,
            'logo' => $image_name,
            'updated_by' => $uId,
          ]);

          if($r)
          {
              $result = ['status' => true, 'message' =>__('orgmgmt')['notification']['org_add_success']];
              return response()->json($result);
          }
          else
          {
              $result = ['status' => false, 'message' =>__('orgmgmt')['notification']['org_add_fail']];
              return response()->json($result);
          }
        }

          $orgs = Organization::where('user_id',\Auth::user()->id)->where('deleted_at',null)->get();
          $projectAlias = config('app.project_alias');
          $addButtonDisabled = false;

          if ($projectAlias != 'sFlow' && count($orgs) >= 1 || $projectAlias == 'sFlow' && count($orgs) >= 2) {
            return response()->json(['message' => __('orgmgmt')['organization_creation_limit_exceeded']], 422);
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
            'show_organization_info' => ($request->organizationinfo) ? true : false,
            'description' => $request->description,
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

          // create default process and template on organization create
          if($projectAlias !== null && $projectAlias == 'sFlow')
          {
            $directory = new \App\Models\Directory;
            $directory->name = $request->name;
            $directory->organization_id = $r->id;
            $directory->responsible_person = \Auth::user()->id;
            $directory->type = 'default';
            $directory->directory_guid = Str::uuid();
            $directory->status = 0;
            $directory->is_protected = 1;
            $directory->created_by = \Auth::user()->id;
            $directory->save();

            $branch = new \App\Models\Branch;
            $branch->text = $request->name;
            $branch->organization_id = $r->id;
            $branch->type = 'default';
            $branch->branch_guid = Str::uuid();
            $branch->is_protected = 1;
            $branch->responsible_person = \Auth::user()->id;
            $branch->created_by = \Auth::user()->id;
            $branch->save();
          }

          if ($projectAlias != 'sFlow' && count($orgs) >= 1 || $projectAlias == 'sFlow' && count($orgs) >= 2) {
            $addButtonDisabled = true;
          }

          if($r)
          {
              $result = ['status' => true, 'message' =>__('orgmgmt')['notification']['org_add_success'], 'addButtonDisabled' => $addButtonDisabled];
              return response()->json($result);
          }
          else
          {
              $result = ['status' => false, 'message' =>__('orgmgmt')['notification']['org_add_fail']];
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
    if (!auth()->user()->can('invite_to_organization') || (!auth()->user()->isOrganizationOwner(session('organization_id')) && !auth()->user()->isOrganizationAdmin(session('organization_id')))) {
      return redirect()->back()->with(['flash_message_error' => __('orgmgmt')['notification']['no_invite_org_perm']]);
    }

    if(!auth()->user()->isOwnerOfOrganization())
    {
      return redirect()->back()->with(['flash_message_error' => __('orgmgmt')['notification']['update_org_settings']]);
    }

    return view('orgmgmt::organizations.invite');
  }

  public function sendInvite(Request $request)
  {
    if (!auth()->user()->can('invite_to_organization') || (!auth()->user()->isOrganizationOwner(session('organization_id')) && !auth()->user()->isOrganizationAdmin(session('organization_id')))) {
      return response()->json(['message' => __('orgmgmt')['notification']['no_invite_org_perm']], 422);
    }

    $rules = [
      'email' => 'required|email',
      // 'invite_message' => 'required'
    ];

    $messages = [
      // 'invite_message.required' => __('orgmgmt')['validation']['invite_message_required'],
      'email.required' => __('orgmgmt')['validation']['required'],
      'email.email' => __('orgmgmt')['validation']['email'],
    ];

    $validation = Validator::make($request->all(), $rules, $messages);

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
        if(session('organization_id')){
          $org = Organization::find(session('organization_id'));
        }
      }

      if($org)
      {
        //validation for own email
        if(!isset($request->id_edit) && !$request->id_edit)
        {
          if($user->email == $request->email)
          {
            $validation->getMessageBag()->add('email', __('orgmgmt')['validation']['owner_org']);
            $result = ['status' => false, 'message' => $validation->errors(), 'data' => []];
            return response()->json($result);
          }
        }

        $existCheck = '';
        // Check if invited email is registered or not
        $toUser = User::where('email',$request->email)->first();
        if($toUser)
          $existCheck = UserOrganization::where('user_id',$toUser->id)->where('organization_id',$org->id)->first();

        // validation for already member of organization
        if($existCheck)
        {
          $validation->getMessageBag()->add('email', __('orgmgmt')['validation']['already_registered']);
          $result = ['status' => false, 'message' => $validation->errors(), 'data' => []];
          return response()->json($result);
        }
      }else{
        return response()->json(['message' => __('orgmgmt')['notification']['something_wrong']], 422);
      }

      // $existingInvite = InvitedUser::where('email',$request->email)->where('organization_id',$org->id)->where('is_registered',0)->first();

      // if(!$toUser && !$existingInvite) // removed as part of multiple invitation allowed sbas-378 same for above line
      if(!$toUser)
      {
        $invitedUsr = new InvitedUser;
        $invitedUsr->email = $request->email;
        $invitedUsr->invite_message = $request->invite_message;
        $invitedUsr->organization_id = $org->id;
        $invitedUsr->invited_by = $user->id;
        $res = $invitedUsr->save();

        $orgInvitation1 = new OrgInvitationLog;
        $orgInvitation1->organization_id = $org->id;
        $orgInvitation1->to_email = $request->email;
        $orgInvitation1->invite_message = $request->invite_message;
        $orgInvitation1->member_type = $request->member_type;
        $orgInvitation1->invited_by = $user->id;
        $orgInvitation1->save();

        // Email user a mail for invitation
        $translationString1 = __('orgmgmt')['mails']['invite_block1'];
        if($org->id == config('app.up_organization_id')){          // for uplandcare email 'de' is by default
          $tempLang = app()->getLocale();
          app()->setLocale('de');
          $translationString1 = __('orgmgmt')['mails']['invite_block1'];
          app()->setLocale($tempLang);
        }
        $translatedText1 = str_replace('<<Organization name>>', $org->name, $translationString1);
        $msgblock2 = str_replace('<<Invitee name>>', $user->name, $translatedText1);


        $toEmail = $request->email;
        $from = $user->email;
        $toName = ''; // as name would not be available from input or db

        $locale = app()->getLocale();

        $data = [
            'organization_id' => $org->id,
            'organization_name' => $org->name,
            'organization_email' => $org->email,
            'user_name' => $user->name,
            'invite_message' => $request->invite_message,
            'msgblock1' => $msgblock2,
            'toName' => $toName,
            'email' => $request->email,
            'urlApprove' => URL::temporarySignedRoute('register-signed', now()->addDays(5), ['org' => $org->short_name,'email' => $request->email, 'action' => 'approve']),
            'urlReject' => URL::temporarySignedRoute('invite-rejected', now()->addDays(5), ['locale' => $locale, 'org' => $org->short_name,'email' => $request->email, 'action' => 'reject']),
        ];
        Mail::to($toEmail)->send(new InviteNonRegisteredUserMail($data,$from));

        if($res)
        {
          $result = ['status' => true, 'message' => __('orgmgmt')['notification']['invi_sent'], 'data' => []];
          return response()->json($result);
        }
        else{
          $result = ['status' => false, 'message' => __('orgmgmt')['notification']['something_wrong'], 'data' => []];
          return response()->json($result);
        }
      }

      // removed as part of multiple invitation allowed sbas-378
      // if($existingInvite)
      // {
      //   return response()->json(['message' => __('orgmgmt')['validation']['already_sent']], 422);
      // }

      if($toUser)
      {
        $orgInvitation = new OrgInvitationLog;
        $orgInvitation->organization_id = $org->id;
        $orgInvitation->to_email = $request->email;
        $orgInvitation->invite_message = $request->invite_message;
        $orgInvitation->member_type = $request->member_type;
        $orgInvitation->invited_by = $user->id;
        $orgInvitation->save();

        // Email user a mail for invitation
        $translationString = __('orgmgmt')['mails']['invite_block1'];
        if($org->id == config('app.up_organization_id')){
          $tempLang = app()->getLocale();
          app()->setLocale('de');
          $translationString = __('orgmgmt')['mails']['invite_block1'];
          app()->setLocale($tempLang);
        }
        $translatedText = str_replace('<<Organization name>>', $org->name, $translationString);
        $msgblock1 = str_replace('<<Invitee name>>', $user->name, $translatedText);

        $toEmail = $request->email;
        $toName = $toUser->name;

        $from = $user->email;
        $data = [
            'organization_id' => $org->id,
            'organization_name' => $org->name,
            'organization_email' => $org->email,
            'user_name' => $user->name,
            'msgblock1' => $msgblock1,
            'toName' => $toName,
            'invite_message' => $request->invite_message,
            'urlApprove' => URL::temporarySignedRoute('invite-link', now()->addDays(5), ['org' => $org->short_name,'email' => $request->email, 'action' => 'approve']),
            'urlReject' => URL::temporarySignedRoute('invite-link', now()->addDays(5), ['org' => $org->short_name,'email' => $request->email, 'action' => 'reject']),
        ];
        Mail::to($toEmail)->send(new InviteMail($data,$from));

        $result = ['status' => true, 'message' => __('orgmgmt')['notification']['invi_sent'], 'data' => []];
        return response()->json($result);
      }
      else{
        $validation->getMessageBag()->add('email', __('orgmgmt')['validation']['org_text_1']);
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

      $exists = false;
      $joinSuccess = false;
      $alreadyAction = false;
      $existCheck = UserOrganization::where('user_id',$user->id)->where('organization_id',$organization->id)->first();

      if($existCheck)
      {
        $exists = true;
        $alreadyAction = true;
        return view('orgmgmt::organizations.organization-join',compact('email','org','joinSuccess','action','exists','alreadyAction'));
      }

      // for invitation check
      $invLogCheck = OrgInvitationLog::where('organization_id',$organization->id)
                          ->where('to_email',$email)->where('invitation_status','=',0)->first();

      $invActRejLogCheck = OrgInvitationLog::where('organization_id',$organization->id)
                          ->where('to_email',$email)->whereIn('invitation_status',[1,2])->first();

      if(!$invLogCheck && $invActRejLogCheck)
      {
        $alreadyAction = true;
        return view('orgmgmt::organizations.organization-join',compact('email','org','joinSuccess','action','exists','alreadyAction'));
      }

      //to add member_type in User organization
      $invLogCheckZero = OrgInvitationLog::where('organization_id',$organization->id)
                          ->where('to_email',$email)->where('invitation_status','=',0)->latest()->first();

      $invLog = OrgInvitationLog::where('organization_id',$organization->id)
                          ->where('to_email',$email)
                          ->update(['invitation_status' => ($action == 'approve' ? 1 : 2)]);

      if($action == 'approve')
      {
        $userOrg = new UserOrganization;
        $userOrg->user_id = $user->id;
        $userOrg->organization_id = $organization->id;
        $userOrg->user_type = 'users';
        $userOrg->access_type = $invLogCheckZero->member_type ?? 2; // 1 for owner, 2 for member
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

              if($action == 'approve')
                $translationString1 = __('orgmgmt')['mails']['invite_response_accept_block1'];
              else
                $translationString1 = __('orgmgmt')['mails']['invite_response_reject_block1'];
              $translatedText1 = str_replace('<<Organization name>>', $orgObj->name, $translationString1);
              $msgblock1 = str_replace('<<first name>>', $user->name, $translatedText1);


              $data = [
                  'organization_id' => $orgObj->id,
                  'organization_name' => $orgObj->name,
                  'organization_email' => $orgObj->email,
                  'sender_name' => $user->name,
                  'user_name' => $userObj->name,
                  'msgblock1' => $msgblock1,
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
      return redirect()->back()->with(['flash_message_error' => __('orgmgmt')['notification']['no_member_view_perm']]);
    }

    if(!$this->isOrganizationAdmins())
    {
      // return redirect()->back()->with(['flash_message_error' => trans('usermgmt::notification.update_org_settings')]);
      return redirect()->back()->with(['flash_message_error' => __('orgmgmt')['notification']['no_member_view_perm']]);
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
              'sEmptyTable': '" . __('orgmgmt')['event']['table']['empty'] . "',
              'sInfo': '" . __('orgmgmt')['event']['table']['info']['sh'] . " ' + '_START_ ' + '" . __('orgmgmt')['event']['table']['info']['to'] . " ' +
                ' _END_' + ' " . __('orgmgmt')['event']['table']['info']['of'] . " ' + '_TOTAL_' +
                ' " . __('orgmgmt')['event']['table']['info']['ent'] . "',
              'sInfoEmpty': '" . __('orgmgmt')['event']['table']['empty'] . "',
              'sInfoFiltered': '(" . __('orgmgmt')['event']['table']['filter']['pre'] . " _MAX_ " . __('orgmgmt')['event']['table']['filter']['post'] . ")',
              'sInfoPostFix': '',
              'sInfoThousands': '" . __('orgmgmt')['event']['table']['thousand_separator'] . "',
              'sLengthMenu': '" . __('orgmgmt')['event']['table']['info']['length_a'] . " ' + '_MENU_' +
                ' " . __('orgmgmt')['event']['table']['info']['length_b'] . "',
              'sLoadingRecords': '" . __('orgmgmt')['event']['table']['loading'] . "',
              'sProcessing': '" . __('orgmgmt')['event']['table']['processing'] . "',
              'sSearch': '" . __('orgmgmt')['event']['table']['sc'] . "',
              'sZeroRecords': '" . __('orgmgmt')['event']['table']['nr'] . "',
              'oPaginate': {
                'sFirst': '" . __('orgmgmt')['event']['table']['paginate']['first'] . "',
                'sLast': '" . __('orgmgmt')['event']['table']['paginate']['last'] . "',
                'sNext': '" . __('orgmgmt')['event']['table']['paginate']['next'] . "',
                'sPrevious': '" . __('orgmgmt')['event']['table']['paginate']['prev'] . "',
              },
              'oAria': {
                'sSortAscending': '" . __('orgmgmt')['event']['table']['sort']['asc'] . "',
                'sSortDescending': '" . __('orgmgmt')['event']['table']['sort']['desc'] . "'
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
      if(session('organization_id'))
        $org = Organization::find(session('organization_id'));
      else
        $org = Organization::where('user_id',$user->id)->first();
    }

    $owner = __('orgmgmt')['form']['owner'];
    $admin = __('orgmgmt')['form']['admin'];
    $member = __('orgmgmt')['form']['member'];
    $contributor = __('orgmgmt')['form']['contributor'];

    $result = DB::table('user_organizations')
            ->leftJoin('users','user_organizations.user_id','=','users.id')
            ->leftJoin('organizations','user_organizations.organization_id','=','organizations.id')
            ->where('user_organizations.organization_id', $org->id)
            ->select('user_organizations.id','users.name','users.email','user_organizations.access_type',DB::raw('(CASE user_organizations.access_type WHEN 1 THEN "'.$owner.'" WHEN 3 THEN "'.$admin.'" WHEN 2 THEN "'.$member.'" WHEN 4 THEN "'.$contributor.'" ELSE "" END) AS member_type'));


      if ($request->ajax()) {
        if ($request->has('order.0.column') && $request->input('order.0.column') == 2) { // Adjust the column index as per your 'member_type' column
            $direction = $request->input('order.0.dir') === 'asc' ? 'ASC' : 'DESC';
            $result->orderBy('member_type', $direction);
          }

        $dataTable = DataTables::queryBuilder($result)
          ->addColumn('actions', function ($data) {
             $button = '';
            if (auth()->user()->can('member_type_change')) {
              $button .= '<button class="btn btn-primary waves-effect waves-light edit" id="' . $data->id . '" data-toggle="tooltip" data-placement="right" title="Edit type" data-member="'.$data->access_type.'"><i class="fa fa-edit"></i></button>';
              $button .= '<button class="btn btn-danger waves-effect waves-light ml-2 remove" id="' . $data->id . '" data-toggle="tooltip" data-placement="right" title="remove from member"><i class="fa-solid fa-right-from-bracket"></i></button>';
            }


            return $button;
          })->rawColumns(['actions']);

          if ($request->has('search.value')) { // Adjust the column index as per your 'member_type' column
              $searchValue = $request->input('search.value'); // Adjust the column index as per your 'member_type' column
              $dataTable->filterColumn('member_type', function ($query, $keyword) use ($searchValue, $owner, $admin, $member, $contributor) {
                  $query->whereRaw('(CASE user_organizations.access_type WHEN 1 THEN "'.$owner.'" WHEN 3 THEN "'.$admin.'" WHEN 2 THEN "'.$member.'" WHEN 4 THEN "'.$contributor.'" ELSE "" END) LIKE ?', ["%$searchValue%"]);
              });
          }

          return $dataTable->toJson();
      }
  }

  public function changeMemberType(Request $request)
  {
    if(!auth()->user()->can('member_type_change'))
    {
      return response()->json(['message' => __('orgmgmt')['notification']['no_member_type_change_perm']], 422);
    }

    if(!auth()->user()->isOrganizationOwner(session('organization_id')))
    {
      return response()->json(['message' => __('orgmgmt')['notification']['no_member_type_change_perm']], 422);
    }

    $rules = [
      'member_type' => 'required',
    ];

    $messages = [
      'member_type.required' => __('orgmgmt')['validation']['sel_mem_type'],
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
            $validation->getMessageBag()->add('member_type', __('orgmgmt')['validation']['org_val_1']);
            $result = ['status' => false, 'message' => $validation->errors(), 'data' => []];
            return response()->json($result);
          }

          $userOrg->access_type = $request->member_type;
          $userOrg->save();

          $result = ['status' => true, 'message' => __('orgmgmt')['notification']['mem_type_changed'], 'data' => []];
          return response()->json($result);
        }
      }
      $result = ['status' => false, 'message' => __('orgmgmt')['notification']['mem_type_change_fail'], 'data' => []];
      return response()->json($result);
    }
  }

  public function list(Request $request)
  {
    if (!auth()->user()->can('organization_list')) {
      return redirect()->back()->with(['flash_message_error' => __('orgmgmt')['notification']['no_org_list_perm']]);
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
        $listRoute = route('org.members.list').'?name='.$data->short_name;
        $button = '<a class="btn btn-primary waves-effect waves-light edit" target="_blank" data-id="'.$data->id.'" data-toggle="tooltip" data-placement="right" title="Edit"><i class="fa fa-edit"></i></a>';
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
    return redirect('/');
    // if (config('app.project_alias')) {
    // }

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
        return redirect()->back()->with(['flash_message_error' => __('orgmgmt')['notification']['no_org_edit_perm']]);
      }

      if($request->name)
      {
        $org = Organization::where('short_name',$request->name)->where('deleted_at',null)->first();

        if($org)
        {
          return view('orgmgmt::organizations.edit',compact('org'));
        }

        return redirect()->back()->with(['flash_message_error' => __('orgmgmt')['notification']['no_org_found']]);
      }
      else
      {
        return redirect()->back()->with(['flash_message_error' => __('orgmgmt')['organization']['form']['error']]);
      }
  }

  public function editUpdate(Request $request)
  {
    if (!auth()->user()->can('organization_edit')) {
      return response()->json(['message' => __('orgmgmt')['notification']['no_org_edit_perm']], 422);
    }

    $rules = [
          'name' => 'required|max:100',
          'short_name_available' => 'sometimes',
          'short_name' => 'required_with:short_name_available,on|max:50|string|alpha_num',
          'email_forward' => 'required|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/',
          'logo' => 'mimes:jpeg,jpg,png,gif|sometimes|max:2048',
      ];

      $messages = [
        'logo.max' => __('orgmgmt')['validation']['logo']['max'],
        'name.required' => __('orgmgmt')['validation']['required'],
        'name.max' => __('orgmgmt')['validation']['max']['string'],
        'short_name.max' => __('orgmgmt')['validation']['max']['string'],
        'short_name.required_with' => __('orgmgmt')['validation']['required_with'],
        'email_forward.required' => __('orgmgmt')['validation']['required'],
        'email_forward.regex' => __('orgmgmt')['validation']['regex'],
      ];

      $validation = Validator::make($request->all(), $rules, $messages);

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
            'default_footer' => $request->default_footer,
          ]);

          if($r)
          {
              $result = ['status' => true, 'message' =>__('orgmgmt')['notification']['org_add_success']];
              return response()->json($result);
          }
          else
          {
              $result = ['status' => false, 'message' =>__('orgmgmt')['notification']['org_add_fail']];
              return response()->json($result);
          }
      }
  }

  public function memberlist(Request $request)
  {
    if (!auth()->user()->can('members_list')) {
      return redirect()->back()->with(['flash_message_error' => __('orgmgmt')['notification']['no_mem_list_perm']]);
    }

    if($request->name)
    {
      $org = Organization::where('short_name',$request->name)->where('deleted_at',null)->first();

      if($org)
      {
        $lang = $this->get_DataTable_LanguageBlock();
        return view('orgmgmt::organizations.member-list',compact('org','lang'));
      }

      return redirect()->back()->with(['flash_message_error' => __('orgmgmt')['notification']['no_org_found']]);
    }
    else
    {
      return redirect()->back()->with(['flash_message_error' => __('orgmgmt')['form']['error']]);
    }
  }

  public function mylist(Request $request)
  {
    $lang = $this->get_DataTable_LanguageBlock();
    $createButtonDisabled = false;

    // to check if user exceeded organization creation limit
    $orgs = Organization::where('user_id',\Auth::user()->id)->where('deleted_at',null)->get();
    $projectAlias = config('app.project_alias');

    if ($projectAlias != 'sFlow' && count($orgs) >= 1 || $projectAlias == 'sFlow' && count($orgs) >= 2) {
      $createButtonDisabled = true;
    }
    return view('orgmgmt::organizations.my-organizations',compact('lang','createButtonDisabled'));
  }

  public function getMyList(Request $request)
  {
    $results = Organization::whereHas('userOrganizations', function ($query) {
                $query->where('user_id', auth()->user()->id)
                      ->whereIn('access_type', [1, 3]);
            })
            ->with(['userOrganizations' => function ($query) {
                $query->where('user_id', auth()->user()->id)
                      ->whereIn('access_type', [1, 3]);
            }])
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
      if(!$this->isOrganizationAdmins($request->id) && !auth()->user()->can('organization_edit'))
      {
        return response()->json(['message' => __('orgmgmt')['notification']['no_org_edit_perm']], 422);
      }

      $model = Organization::find($request->id);

      $result = ['status' => true, 'message' => '', 'detail' => $model];
      return response()->json($result);

    }
    else{
      return response()->json(['message' => __('orgmgmt')['notification']['no_org_found']], 422);
    }

  }

  public function isOrganizationAdmins($organizationId = null)
  {
    $organizationId = $organizationId ?? session('organization_id');

    $orgAdmin = UserOrganization::where('organization_id', $organizationId)->where('user_id', auth()->user()->id)->whereIn('access_type', [1,3])->first();
    if($orgAdmin)
    {
      return true;
    }
    return false;
  }
}