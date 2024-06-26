<?php

namespace Sbash\Orgmgmt\Controllers;

use App\Models\OrganizationRequest;
use Sbash\Orgmgmt\Models\OrganizationJoinRequest;
use Sbash\Orgmgmt\Models\UserOrganization;
use Sbash\Orgmgmt\Models\Organization;
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

class OrganizationRequestController extends Controller
{
    public function __construct()
    {
        if(class_exists('App\Http\Middleware\PreventBackHistory')){
            $this->middleware(['preventBackHistory']);
        }
    }

    public function joinRequest(Request $request)
    {
        $user = \Auth::user();
        if ($request->ajax()){

            $rules = [
                'id' => 'required'
            ];

            $validation = Validator::make($request->all(), $rules);

            if ($validation->fails()) {
                $result = ['status' => false, 'message' => $validation->errors(), 'data' => []];
                return response()->json($result);
            }

            $exists = $user->isMemberOfOrganization($request->id);

            if($exists)
            {
                $validation->errors()->add('already_member',__('orgmgmt')['notification']['already_member']);

                $result = ['status' => false, 'message' => $validation->errors(), 'data' => []];
                return response()->json($result);
            }

            $checkReq = OrganizationJoinRequest::where('organization_id',$request->id)->where('user_id',$user->id)->first();

            if($checkReq)
            {
                $validation->errors()->add('already_requested',__('orgmgmt')['notification']['already_requested']);

                $result = ['status' => false, 'message' => $validation->errors(), 'data' => []];
                return response()->json($result);
            }

            else
            {
                $joinRequest = new OrganizationJoinRequest;
                $joinRequest->organization_id = $request->id;
                $joinRequest->user_id = $user->id;
                $joinRequest->user_note = $request->note;
                $r = $joinRequest->save();

                $userOrgs = UserOrganization::where('organization_id',$request->id)->where('access_type',1)->get();

                if(count($userOrgs))
                {
                    $orgObj = Organization::find($request->id);
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
                        ];
                        Mail::to($userObj->email)->send(new ApprovalNotificationMail($data,$from));
                    }
                }

                if($r)
                {
                    $result = ['status' => true, 'message' => __('orgmgmt')['notification']['join_request'], 'data' => []];
                    return response()->json($result);
                }
                else{
                    $result = ['status' => false, 'message' => __('orgmgmt')['notification']['join_request_failed'], 'data' => []];
                    return response()->json($result);
                }
            }
        }
    }

    public function index()
    {
        $x = new OrganizationController;
        $lang = $x->get_DataTable_LanguageBlock();

        return view('orgmgmt::organizations.organization-requests',compact('lang'));
    }

    public function getOrgRequests(Request $request)
    {
        $userId = auth()->user()->id;

        $results = OrganizationJoinRequest::join('organizations', 'organization_join_requests.organization_id', '=', 'organizations.id')
                ->join('user_organizations', function($join) use ($userId) {
                    $join->on('organizations.id', '=', 'user_organizations.organization_id')
                        ->where('user_organizations.user_id', '=', $userId)
                        ->where('user_organizations.access_type', '=', 1);
                })
                ->join('users', 'organization_join_requests.user_id', '=', 'users.id')
                ->select('users.name as username','users.email as usermail','organizations.name as orgname','organization_join_requests.created_at as created','organization_join_requests.id as createId','organization_join_requests.is_approved',DB::raw("(CASE
                        WHEN organization_join_requests.is_approved = 1 THEN 'Approved'
                        WHEN organization_join_requests.is_approved = 2 THEN 'Rejected'
                        ELSE 'Pending' END) as status"))
                ->get();

        return DataTables::of($results)
                ->addColumn('actions', function ($data) {
                    $button = '<a class="btn btn-warning waves-effect waves-light ml-1 view-btn" data-toggle="modal" data-target="#myModal" data-toggle="tooltip" data-id="'.$data->createId.'" data-status-id="'.$data->is_approved.'" title="Request">'.__('orgmgmt')['table']['view'].'</a>';
                return $button;
            })->rawColumns(['actions'])
          ->toJson();
    }

    public function details(Request $request)
    {
        if ($request->ajax())
        {
            if($request->id)
            {
                $user = Auth::user();
                if($user->isOwnerOfOrganization())
                {
                    $detail = OrganizationJoinRequest::find($request->id);

                    if($detail)
                    {
                        $result = ['status' => true, 'detail' => $detail];
                        return response()->json($result);
                    }
                }
            }
            else{
                $result = ['status' => false, 'detail' => null];
                return response()->json($result);
            }
        }
    }

    public function action(Request $request)
    {
        if ($request->ajax())
        {
            $rules = [
                'owner_note' => 'max:1000',
            ];

            $validation = Validator::make($request->all(), $rules);

            if ($validation->fails()) {
                $result = ['status' => false, 'message' => $validation->errors(), 'data' => []];
                return response()->json($result);
            }
            else
            {
                $req = OrganizationJoinRequest::find($request->id);
                if($req->is_approved == $request->status)
                {
                    $validation->errors()->add('status_change', __('orgmgmt')['validation']['update_status']);

                    $result = ['status' => false, 'message' => $validation->errors(), 'data' => []];
                    return response()->json($result);
                }
                else{
                    $msg = '';
                    if($request->status == 1)
                    {
                        $msg = __('orgmgmt')['notification']['request_approved'];
                    }
                    else if($request->status == 2)
                    {
                        $msg = __('orgmgmt')['notification']['request_rejected'];
                    }

                    $req->is_approved = $request->status;
                    $req->owner_note = $request->owner_note;
                    $req->action_by = auth()->user()->id;
                    $res = $req->save();

                    // Email notification for actions
                    $orgObj = Organization::find($req->organization_id);
                    $userObj = User::find($req->user_id);

                    $userName = $userObj->name;
                    $from = $orgObj->email;
                    $name = $orgObj->name;
                    $note = $request->owner_note;

                    if($res && $request->status == 1)
                    {
                        $alreadyMember = UserOrganization::where('organization_id',$req->organization_id)->where('user_id',$req->user_id)->first();
                        if(!$alreadyMember)
                        {
                            $userOrg = new UserOrganization;
                            $userOrg->user_id = $req->user_id;
                            $userOrg->organization_id = $req->organization_id;
                            $userOrg->user_type = 'users';
                            $userOrg->access_type = 2; // 1 for owner, 2 for member
                            $userOrg->save();

                            $subject = 'Your request to Join Organization has Approved';
                            $action = 'Approved';

                            // Email user a mail for invitation
                            $toEmail = $request->email;
                            $data = [
                                'userName' => $userName,
                                'name' => $name,
                                'action' => $action,
                                'note' => $note,
                                'subject' => $subject,
                            ];
                            Mail::to($userObj->email)->send(new RequestActionMail($data,$from));
                            // end

                        }
                    }
                    else if($res && $request->status == 2)
                    {
                        $subject = 'Your request to Join Organization has Rejected';
                        $action = 'Rejected';

                        $data = [
                            'userName' => $userName,
                            'name' => $name,
                            'action' => $action,
                            'note' => $note,
                            'subject' => $subject,
                        ];

                        Mail::to($userObj->email)->send(new RequestActionMail($data,$from));
                    }
                    // mail code ends

                    if($res)
                    {
                        $result = ['status' => true, 'message' => $msg];
                        return response()->json($result);
                    }
                    else{
                        $result = ['status' => false, 'message' => __('orgmgmt')['notification']['status_update_fail']];
                        return response()->json($result);
                    }
                }
            }
        }
    }
}
