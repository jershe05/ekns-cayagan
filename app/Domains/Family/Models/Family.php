<?php
namespace App\Domains\Family\Models;

use App\Domains\Auth\Models\User;
use App\Domains\Household\Models\Household;
use Illuminate\Database\Eloquent\Model;

class Family extends Model
{
    protected $table = 'family_trees';
    protected $fillable = [
        'user_id',
        'household_id',
        'mother_id',
        'father_id',
        'spouse_id',
        'created_at',
        'updated_at'
    ];

    public function voter()
    {
        return $this->hasOne(User::class);
    }

    public function household()
    {
        return $this->hasOne(Household::class, 'id', 'household_id');
    }
}
