<?php

namespace App\Domains\Misc\Models;

use App\Domains\Analytics\Models\TotalVotersPerLocation;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema()
 * @OA\Property(
 *      property="id",
 *      type="integer",
 *      description="ID of Barangay",
 *      example=1
 *  )
 * @OA\Property(
 *      property="pgsc",
 *      type="string",
 *      description="PSGC code of Barangay",
 *      example="001"
 *  )
 * @OA\Property(
 *      property="name",
 *      type="string",
 *      description="Name of barangay",
 *      example="Bagumbayan"
 *  )
 * Class Barangay
 * @package App\Domains\Misc\Models
 */
class Barangay extends Model
{
    public function city()
    {
        return $this->belongsTo(City::class, 'city_municipality_code', 'city_municipality_code');
    }

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_code', 'province_code');
    }

    public function region()
    {
        return $this->belongsTo(Region::class, 'region_code', 'region_code');
    }

    public function totalVoters()
    {
        return $this->hasOne(TotalVotersPerLocation::class, 'barangay_code', 'barangay_code');
    }

    public function addresses()
    {
        return $this->hasMany(Address::class, 'barangay_code', 'barangay_code');
    }
}
