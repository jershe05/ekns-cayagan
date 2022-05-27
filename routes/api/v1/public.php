<?php

use Illuminate\Http\Request;
use App\Domains\Auth\Http\Controllers\Api\ApiLoginController;
use App\Domains\Auth\Http\Controllers\Api\User\UserController;
use App\Domains\Misc\Http\Controllers\API\BarangayAPIController;
use App\Domains\Misc\Http\Controllers\API\CityAPIController;
use App\Domains\Misc\Http\Controllers\API\ProvinceAPIController;
use App\Domains\Misc\Http\Controllers\API\RegionAPIController;
use Illuminate\Support\Str;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('login',[ApiLoginController::class, 'login']);
// LOCATIONS

Route::group(['as' => 'locations.'], function () {
    Route::get('regions', [RegionAPIController::class, 'index'])->name('regions');
    Route::get('regions/{region}/provinces', [ProvinceAPIController::class, 'index'])->name('provinces');
    Route::get('provinces/{province}/cities', [CityAPIController::class, 'index'])->name('cities');
    Route::get('cities/{city}/barangays', [BarangayAPIController::class, 'index'])->name('barangays');
});

Route::fallback(function(){
    return response()->json([
        'message' => 'bad request'], 404);
});

Route::get('test', function(){
    dd(Str::random(5));
});


