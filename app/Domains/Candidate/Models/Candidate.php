<?php
namespace App\Domains\Candidate\Models;

use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    protected $fillable = [
        'id',
        'user_id',
        'position_id',
        'scope_id'
    ];
}
