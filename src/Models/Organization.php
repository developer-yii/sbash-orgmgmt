<?php

namespace Sbash\Orgmgmt\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
  use HasFactory;

  // Disable Laravel's mass assignment protection
  protected $guarded = [];

  public function user(){
    return $this->belongsTo('App\Models\User');
  }

  public function requests()
  {
      return $this->hasMany(OrganizationJoinRequest::class);
  }

  public function userOrganizations()
  {
      return $this->hasMany(UserOrganization::class);
  }

}