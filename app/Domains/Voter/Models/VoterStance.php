<?php
namespace App\Domains\Voter\Models;

use Illuminate\Database\Eloquent\Model;

class VoterStance extends Model
{
    protected $fillable = [
        'user_id',
        'stance',
        'created_at',
        'updated_at'
    ];
}
