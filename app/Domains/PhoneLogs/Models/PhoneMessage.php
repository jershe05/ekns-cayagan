<?php
namespace App\Domains\PhoneLogs\Models;

use Illuminate\Database\Eloquent\Model;

class PhoneMessage extends Model
{
    protected $fillable = [
        'user_id',
        'phone',
        'message',
        'date',
        'type',
        'created_at',
        'updated_at'
    ];
}
