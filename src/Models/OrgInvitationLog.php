<?php

namespace Sbash\Orgmgmt\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrgInvitationLog extends Model
{
  use HasFactory;

  protected $table = 'organization_invitation_log';

  protected $guarded = [];
}