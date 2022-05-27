<?php


namespace App\Domains\Misc\Http\Controllers\API;

use App\Domains\Misc\Http\Resources\RegionResourceCollection;
use App\Domains\Misc\Models\Region;

class RegionAPIController
{
    public function index()
    {
        $regions = Region::all();
        return new RegionResourceCollection($regions);
    }
}
