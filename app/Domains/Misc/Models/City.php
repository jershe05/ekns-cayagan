<?php

namespace App\Domains\Misc\Models;

use App\Domains\Analytics\Models\TotalVotersPerLocation;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema()
 * @OA\Property(
 *      property="id",
 *      type="integer",
 *      description="ID of City",
 *      example=1
 *  )
 * @OA\Property(
 *      property="pgsc",
 *      type="string",
 *      description="PSGC code of city",
 *      example="001"
 *  )
 * @OA\Property(
 *      property="name",
 *      type="string",
 *      description="Name of City",
 *      example="Quezon City"
 *  )
 * Class City
 * @package App\Domains\Misc\Models
 */
class City extends Model
{
    public function barangays()
    {
        return $this->hasMany(Barangay::class, 'city_municipality_code', 'city_municipality_code');
    }

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_code', 'province_code');
    }

    public function totalVoters()
    {
        return $this->hasMany(TotalVotersPerLocation::class, 'city_code', 'city_municipality_code');
    }
}
