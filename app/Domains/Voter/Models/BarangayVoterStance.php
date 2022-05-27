<?php
namespace App\Domains\Voter\Models;

use Illuminate\Database\Eloquent\Model;

class BarangayVoterStance extends Model
{
    protected $fillable = [
        'barangay_code',
        'pro',
        'non_pro',
        'undecided',
        'city_code',
        'province_code',
        'region_code',
        'created_at',
        'updated_at'
    ];

}
