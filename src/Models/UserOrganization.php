<?php

namespace Sbash\Orgmgmt\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserOrganization extends Model
{
  use HasFactory;

  protected $table = 'user_organizations';

  public $timestamps = false;

  protected $guarded = [];

  public function organization()
  {
      return $this->belongsTo(Organization::class);
  }
}