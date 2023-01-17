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
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class OrganizationController extends Controller
{
  public function settings(Request $request)
  {
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

          $org = Organization::where('user_id',\Auth::user()->id)->where('deleted_at',null)->first();

          if(!$org)
          {
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
                'logo' => $image_name,
                'user_id' => \Auth::user()->id,      
              ]);

              $userOrg = new UserOrganization;
              $userOrg->user_id = \Auth::user()->id;
              $userOrg->organization_id = $r->id;
              $userOrg->user_type = 'users';
              $userOrg->access_type = 1; // 1 for owner, 2 for member
              $userOrg->save();
          }

          else{

              $image_name = $org->logo;

              if ($request->hasFile('logo')) {
                  $image = $request->file('logo');
                  $image_name = time().rand(1000,9999).'.'.$image->getClientOriginalExtension();
                  $destinationPath = public_path('img/uploads/org_logo');
                  $image->move($destinationPath, $image_name);
              }

              $email = trim($request->short_name);                
              $email = $email.'@sbash.io';

              $r = $org->update([
                'name' => $request->name,
                'short_name' => $request->short_name,
                'email' => $email,              
                'email_forward' => $request->email_forward,              
                'short_name_available' => ($request->short_name_available) ? 1 : 0,              
                'logo' => $image_name,
                'user_id' => \Auth::user()->id,      
              ]);
              $uId = \Auth::user()->id;

              if($r)
              {
                $userOrg = UserOrganization::where('user_id',$uId)->where('organization_id',$org->id)->first();
                if(!$userOrg)
                {
                  $userOrg = new UserOrganization;
                  $userOrg->user_id = \Auth::user()->id;
                  $userOrg->organization_id = $org->id;
                  $userOrg->user_type = 'users';
                  $userOrg->access_type = 1; // 1 for owner, 2 for member
                  $userOrg->save();
                }
              }
          }

          if($r)
          {
              $result = ['status' => true, 'message' =>'Organization update success'];
              return response()->json($result);
          }
          else
          {
              $result = ['status' => false, 'message' =>'Organization update failed'];
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
    return view('orgmgmt::organizations.invite');
  }

  public function sendInvite(Request $request)
  {
    $rules = [
      'email' => 'required|email|exists:users,email',                              
    ];

    $messages = [
      'email.exists' => 'Email is not registered in system',
    ];

    $validation = Validator::make($request->all(), $rules, $messages);

    if ($validation->fails()) {      
        $result = ['status' => false, 'message' => $validation->errors(), 'data' => []];
        return response()->json($result);
    }
    else
    {
      $user = \Auth::user();
      $org = Organization::where('user_id',$user->id)->where('deleted_at',null)->first();
      if($org)
      {
        //validation for own email
        if($user->email == $request->email)
        {
          $validation->getMessageBag()->add('email', 'You are already owner of organization');
          $result = ['status' => false, 'message' => $validation->errors(), 'data' => []];
          return response()->json($result); 
        }

        $toUser = User::where('email',$request->email)->first();
        $existCheck = UserOrganization::where('user_id',$toUser->id)->where('organization_id',$org->id)->first();
        if($existCheck)
        {
          $validation->getMessageBag()->add('email', 'Email already member of organization');
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
            'url' => URL::temporarySignedRoute('invite-link', now()->addDays(5), ['org' => $org->short_name,'email' => $request->email]),
        ];
        Mail::to($toEmail)->send(new InviteMail($data,$from));        

        $result = ['status' => true, 'message' => 'Invitation link sent', 'data' => []];
        return response()->json($result);
      }
      else{
        $validation->getMessageBag()->add('email', 'Organization not created, Please save Organization details in Organization/settings');
        $result = ['status' => false, 'message' => $validation->errors(), 'data' => []];
        return response()->json($result);   
      }
    }
  }

  public function joinOrganization(Request $request, $org, $email)
  {
    if($email && $org)
    {
      $user = User::where('email',$email)->first();
      $organization = Organization::where('short_name',$org)->where('deleted_at',null)->first();

      $existCheck = UserOrganization::where('user_id',$user->id)->where('organization_id',$organization->id)->first();
      $exists = false;
      $joinSuccess = false; 
      if($existCheck)
      {
        return view('orgmgmt::organizations.organization-join',compact('email','org','joinSuccess','exists'));
      }
      $userOrg = new UserOrganization;
      $userOrg->user_id = $user->id;
      $userOrg->organization_id = $organization->id;
      $userOrg->user_type = 'users';
      $userOrg->access_type = 2; // 1 for owner, 2 for member
      $r = $userOrg->save();

      if($r)
      {
        $joinSuccess = true;
        return view('orgmgmt::organizations.organization-join',compact('email','org','joinSuccess','exists'));
      }
      else{
        return view('orgmgmt::organizations.organization-join',compact('email','org','joinSuccess','exists')); 
      }
    }    
  }
}