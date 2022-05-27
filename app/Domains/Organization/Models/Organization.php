<?php

namespace App\Domains\Organization\Models;

use App\Domains\Misc\Traits\HasAddress;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasAddress;
    protected $fillable = [
        'name',
        'created_at',
        'updated_at'
    ];
}
