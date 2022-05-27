<?php
namespace App\Domains\Messages\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{

    protected $fillable = [
        'message',
        'scope',
        'created_at',
        'updated_at'
    ];
}
