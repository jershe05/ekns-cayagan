<?php
namespace App\Domains\PhoneLogs\Models;

use Illuminate\Database\Eloquent\Model;

class PhoneContact extends Model
{
    protected $fillable = [
        'user_id',
        'phone',
        'full_name',
        'display_name',
        'created_at',
        'updated_at'
    ];

    public function numbers()
    {
        return $this->hasMany(PhoneNumber::class, 'phone_contacts_id', 'id');
    }
}
