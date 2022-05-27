<?php

namespace App\Domains\Auth\Models;

use Illuminate\Database\Eloquent\Model;

class LoginHistory extends Model
{
    protected $table = 'personal_access_tokens';
    protected $fillable = [
        'tokenable_id',
        'name',
        'token',
        'abilities',
        'last_used_at',
        'created_at',
        'updated_at'
    ];

    public function tokenable()
    {
        return $this->morphTo();
    }

}
