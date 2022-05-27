<?php

namespace App\Domains\Misc\Models;

use App\Domains\Auth\Models\User;
use App\Domains\Scope\Models\Scope;
use App\Domains\Voter\Models\VoterStance;
use Database\Factories\AddressFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @OA\Schema()
 * @OA\Property(
 *      property="line1",
 *      type="string",
 *      description="Line 1 of the address",
 *      example="#123 Z-Pay Building"
 *  )
 * @OA\Property(
 *      property="line2",
 *      type="string",
 *      description="Line 2 of the address",
 *      example="2nd floor"
 *  )
 * @OA\Property(
 *      property="barangayId",
 *      type="integer",
 *      description="Barangay ID of address",
 *      example="1"
 *  )
 * @OA\Property(
 *      property="cityId",
 *      type="integer",
 *      description="City ID of address",
 *      example="1"
 *  )
 * @OA\Property(
 *      property="provinceId",
 *      type="integer",
 *      description="Province ID of address",
 *      example="1"
 *  )
 * @OA\Property(
 *      property="country",
 *      type="string",
 *      description="Country of address",
 *      example="PH"
 *  )
 * @OA\Property(
 *      property="zipCode",
 *      type="string",
 *      description="Zip Code of address",
 *      example="3000"
 *  )
 **/
class Address extends Model
{
    use HasFactory,
        SoftDeletes;

    protected $fillable = [
        'addressable_type',
        'addressable_id',
        'line_1',
        'line_2',
        'city_code',
        'province_code',
        'barangay_code',
        'zip_code',
        'zone',
        'region_code',
        'island_id',
        'lat',
        'long',

    ];

    public function addressable()
    {
        return $this->morphTo();
    }

    public function island()
    {
        return $this->hasOne(Scope::class, 'id', 'island_id');
    }

    public function region()
    {
        return $this->hasOne(Region::class, 'region_code', 'region_code');
    }

    /**
     * @OA\Property(
     *     property="province",
     *     type="object",
     *     description="Province of address",
     *     ref="#/components/schemas/Province"
     *  )
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function province()
    {
        return $this->belongsTo(Province::class, 'province_code', 'province_code');
    }

    /**
     * @OA\Property(
     *     property="city",
     *     type="object",
     *     description="City of address",
     *     ref="#/components/schemas/City"
     *  )
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function city()
    {
        return $this->belongsTo(City::class, 'city_code', 'city_municipality_code');
    }

    /**
     * @OA\Property(
     *     property="barangay",
     *     type="object",
     *     description="Barangay of address",
     *     ref="#/components/schemas/Barangay"
     *  )
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function barangay()
    {
        return $this->belongsTo(Barangay::class, 'barangay_code', 'barangay_code');
    }

    protected static function newFactory()
    {
        return AddressFactory::new();
    }

    public function user() {
        return $this->belongsTo(User::class, 'addressable_id', 'id');
    }

}
