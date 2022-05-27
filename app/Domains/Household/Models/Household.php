<?php
namespace App\Domains\Household\Models;

use App\Domains\Auth\Models\User;
use App\Domains\Family\Models\Family;
use Illuminate\Database\Eloquent\Model;

class Household extends Model {
    protected $fillable = [
        'household_name',
        'user_id',
        'created_at',
        'updated_at',
        'leader_id'
    ];

    public function families()
    {
        return $this->hasMany(Family::class);
    }

    public function leader()
    {
        return $this->hasOne(User::class, 'id', 'leader_id');
    }
}
