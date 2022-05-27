<?php
namespace App\Domains\PhoneLogs\Models;

use Illuminate\Database\Eloquent\Model;

class PhoneNumber extends Model
{
    protected $fillable = [
        'phone_contacts_id',
        'number',
        'created_at',
        'updated_at'
    ];

    public function contact()
    {
        return $this->belongsTo(PhoneContact::class);
    }
}
