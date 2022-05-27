<?php

namespace App\Domains\Leader\Models;

use App\Domains\Auth\Models\User;
use App\Domains\Misc\Traits\HasAddress;
use Illuminate\Database\Eloquent\Model;
use App\Domains\Organization\Models\Organization;
class Leader extends Model
{
    use HasAddress;
    protected $fillable = [
        'user_id',
        'scope_id',
        'candidate_id',
        'type',
        'referred_by',
        'organization_id',
        'created_at',
        'updated_at'
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function upperLeader()
    {
        return $this->hasOne(Leader::class, 'id', 'referred_by');
    }

}
