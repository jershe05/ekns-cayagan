<?php


namespace App\Domains\Misc\Http\Controllers\API;

use Illuminate\Support\Facades\DB;
use F9Web\ApiResponseHelpers;

class ScopeController
{
    use ApiResponseHelpers;
    public function index()
    {
        return $this->respondWithSuccess(['data' =>DB::table('scopes')->get()]);
    }
}
