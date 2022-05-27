<?php
namespace App\Domains\Analytics\Models;

use Illuminate\Database\Eloquent\Model;

class TotalVotersPerLocation extends Model
{
    protected $table = 'total_voters_per_location';
    protected $fillable = [
        'total_voters',
        'barangay_code',
        'city_code',
        'province_code',
        'region_code',
        'created_at',
        'updated_at'
    ];
}
