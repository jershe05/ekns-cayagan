<?php


namespace App\Domains\Misc\Http\Controllers\API;

use App\Domains\Misc\Http\Resources\BarangayResourceCollection;
use App\Domains\Misc\Models\City;

class BarangayAPIController
{
    public function index(City $city)
    {
        return new BarangayResourceCollection($city->barangays);
    }
}
