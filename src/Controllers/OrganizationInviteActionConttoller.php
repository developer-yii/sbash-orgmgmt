<?php

namespace Sbash\Orgmgmt\Controllers;

use Sbash\Orgmgmt\Models\UserOrganization;
use Sbash\Orgmgmt\Models\Organization;
use App\Models\User;
use Sbash\Orgmgmt\Models\OrgInvitationLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Sbash\Orgmgmt\Mail\InvitationActionMail;
use DataTables;
use DB;
use Auth;

class OrganizationInviteActionConttoller extends Controller
{
	public function rejectJoin(Request $request, $org, $email, $action)
	{
		if($email && $org)
	    {
	      	$user = User::where('email',$email)->first();
	      	$organization = Organization::where('short_name',$org)->where('deleted_at',null)->first();
	      	$existCheck = '';
	      	$exists = false;
	      	$joinSuccess = false;
	      	$alreadyAction = false;       

	      	if($user)
	      	{
	      		$existCheck = UserOrganization::where('user_id',$user->id)->where('organization_id',$organization->id)->first();
	      	}

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
	                ->update(['invitation_status' => 2]);      	      

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
	                  	'sender_name' => $email,
	                  	'user_name' => $userObj->name,
	                  	'action' => $action
	              	];
	              	Mail::to($userObj->email)->send(new InvitationActionMail($data,$from));        
	          	}
	      	}
	     
	      	return view('orgmgmt::organizations.organization-join',compact('email','org','joinSuccess','exists','action','alreadyAction')); 	      
	    }
	}	
}