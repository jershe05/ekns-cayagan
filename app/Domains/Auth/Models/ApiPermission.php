<?php

namespace App\Domains\Auth\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Permission.
 */
class ApiPermission extends Model
{
    protected $table = 'permissions';
    protected $fillable = [
        'id',
        'type',
        'name',
        'description',
        'parent_id',
        'sort',
        'created_at',
        'updated_at'
    ];
}
