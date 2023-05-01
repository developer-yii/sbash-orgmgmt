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
	
}