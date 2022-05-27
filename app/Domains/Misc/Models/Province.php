<?php

namespace App\Domains\Misc\Models;

use App\Domains\Analytics\Models\TotalVotersPerLocation;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema()
 * @OA\Property(
 *      property="id",
 *      type="integer",
 *      description="ID of Province",
 *      example=1
 *  )
 * @OA\Property(
 *      property="pgsc",
 *      type="string",
 *      description="PSGC code of Province",
 *      example="0002"
 *  )
 * @OA\Property(
 *      property="name",
 *      type="string",
 *      description="Name of province",
 *      example="Bulacan"
 *  )
 * Class Province
 * @package App\Domains\Misc\Models
 */
class Province extends Model
{
    public function cities()
    {
        return $this->hasMany(City::class, 'province_code', 'province_code');
    }

    public function totalVoters()
    {
        return $this->hasMany(TotalVotersPerLocation::class, 'province_code', 'province_code');
    }

    public function region()
    {
        return $this->belongsTo(Region::class, 'region_code', 'region_code');
    }
}
