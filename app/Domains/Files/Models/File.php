<?php

namespace App\Domains\Files\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $fillable = [
        'user_id',
        'file',
        'directory_s3',
        'date',
        'description',
        'created_at',
        'updated_at'
    ];


}
