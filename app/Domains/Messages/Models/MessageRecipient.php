<?php
namespace App\Domains\Messages\Models;

use Illuminate\Database\Eloquent\Model;
use App\Domains\Leader\Models\Leader;
class MessageRecipient extends Model
{
    protected $fillable = [
        'message_id',
        'leader_id',
        'created_at',
        'updated_at'
    ];


    public function leader()
    {
        return $this->belongsTo(Leader::class, 'leader_id', 'id');
    }

    public function message()
    {
        return $this->belongsTo(Message::class);
    }
}
