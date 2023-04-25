<?php

namespace Sbash\Orgmgmt\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrganizationJoinRequest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'organization_id',
        'user_id',
        'user_note',
        'owner_note',
        'is_approved',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function joinRequests()
    {
        return OrganizationJoinRequest::where('user_id', $this->id)
            ->with('organization', 'user')
            ->get();
    }

    public function approve()
    {
        $this->is_approved = 1;
        $this->save();
    }

    public function reject()
    {
        $this->is_approved = 0;
        $this->save();
    }
}
