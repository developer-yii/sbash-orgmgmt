<?php

namespace Sbash\Orgmgmt\Controllers;

use App\Models\OrganizationRequest;
use Sbash\Orgmgmt\Models\OrganizationJoinRequest;
use Sbash\Orgmgmt\Models\UserOrganization;
use Sbash\Orgmgmt\Models\Organization;
use Sbash\Orgmgmt\Models\InvitedUser;
use App\Models\User;
use Sbash\Orgmgmt\Controllers\OrganizationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Sbash\Orgmgmt\Mail\ApprovalNotificationMail;
use Sbash\Orgmgmt\Mail\RequestActionMail;
use DataTables;
use DB;
use Auth;

class InvitedUserController extends Controller
{
	public function remove(Request $request)
	{
		$user = Auth::user();

		if($request->id)
		{
			if($this->isOrganizationAdmins() || $user->hasAnyRole(['level_2', 'level_3']))
			{
				$userOrganization = UserOrganization::find($request->id);

				if($userOrganization->access_type == 1)
				{
					$result = ['status' => false, 'message' => __('orgmgmt')['notification']['cant_remove_owner']];
      				return response()->json($result);
				}

				// sflow-252
				$adminCount = UserOrganization::where('organization_id', $userOrganization->organization_id)->where('access_type', 3)->count();
				if($userOrganization->access_type == 3 && $adminCount <= 1){
				  return response()->json(['message' => __('orgmgmt')['notification']['atleast_one_admin_member_required']], 422);
				}

				$r = $userOrganization->delete();

				if($r)
				{
					$result = ['status' => true, 'message' => __('orgmgmt')['notification']['member_remove_success']];
      				return response()->json($result);
				}else {
					$result = ['status' => false, 'message' => __('orgmgmt')['notification']['member_remove_failed']];
      				return response()->json($result);
				}
			} else {
				return response()->json(['message' => __('orgmgmt')['notification']['no_member_remove_perm']], 422);
			}
		}
	}

	public function isOrganizationAdmins()
	{
		$orgAdmin = UserOrganization::where('organization_id', session('organization_id'))->where('user_id', auth()->user()->id)->whereIn('access_type', [1,3])->first();
		if($orgAdmin)
		{
			return true;
		}
		return false;
	}
}