<?php
namespace App\Domains\Misc\Repositories;

use App\Domains\Misc\Models\Address;
use App\Domains\Misc\Models\Barangay;
use App\Domains\Misc\Models\City;
use App\Domains\Misc\Models\Province;
use App\Domains\Misc\Models\Region;

class AddressRepository {

    public static function getBarangay(Address $address)
    {
       return [
            'barangay_id' => $address->barangay->id,
            'barangay_code' => $address->barangay->barangay_code ?? '',
            'barangay_description' => $address->barangay->barangay_description ?? '',
            'zone_no' => $address->zone_no ?? 1
       ];
    }

    public static function getCity(City $city)
    {
       return [
            'city_id' => $city->id,
            'city_code' =>  $city->city_municipality_code ?? '',
            'city_description' =>  $city->city_municipality_description ?? ''
       ];
    }

    public static function getProvince(Province $province)
    {
        return [
            'province_id' => $province->id,
            'province_code' => $province->province_code ?? '',
            'province_description' => $province->province_description ?? ''
        ];
    }

    public static function getRegion(Region $region)
    {
        return [
            'region_id' => $region->id,
            'region_code' => $region->region_code ?? '',
            'region_description' => $region->region_description ?? ''
        ];
    }
}
