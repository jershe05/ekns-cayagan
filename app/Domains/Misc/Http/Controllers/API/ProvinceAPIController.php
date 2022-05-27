<?php


namespace App\Domains\Misc\Http\Controllers\API;

use App\Domains\Misc\Http\Resources\ProvinceResourceCollection;
use App\Domains\Misc\Models\Province;
use App\Domains\Misc\Models\Region;

class ProvinceAPIController
{
    public function index(Region $region)
    {
        return new ProvinceResourceCollection($region->provinces);
    }
}
