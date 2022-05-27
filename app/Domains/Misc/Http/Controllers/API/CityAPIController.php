<?php
namespace App\Domains\Misc\Http\Controllers\API;

use App\Domains\Misc\Http\Resources\CityResourceCollection;
use App\Domains\Misc\Models\Province;

class CityAPIController
{
    public function index(Province $province)
    {
        return new CityResourceCollection($province->cities);
    }
}
