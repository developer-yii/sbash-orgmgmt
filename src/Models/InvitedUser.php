<?php

namespace Sbash\Orgmgmt\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvitedUser extends Model
{
  use HasFactory;

  // Disable Laravel's mass assignment protection
  protected $guarded = [];

  protected $table = 'invited_users';  

}