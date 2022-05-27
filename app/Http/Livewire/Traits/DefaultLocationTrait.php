<?php
namespace App\Http\Livewire\Traits;

trait DefaultLocationTrait
{
    public $locationBase = [
        'island_id' => null,
        'region_code' => null,
        'province_code' => null,
        'city_code' => null,
        'barangay_code' => null
    ];

    public $locationLevel;

    public function selectLocationLevel()
    {

        if(auth()->user()->address->province_code) {
            $this->locationLevel = 'provincial';
            $this->locationBase['province_code'] = auth()->user()->address->province_code;
            if (isset($_GET['city'])) {
                $this->locationBase['city_code'] = $_GET['city'];
            }

            if (isset($_GET['barangay'])) {
                $this->locationBase['barangay_code'] = $_GET['barangay'];
            }
        } else if(auth()->user()->address->city_code) {
            $this->locationLevel = 'city';
            $this->locationBase['city_code'] = auth()->user()->address->city_code;
            if (isset($_GET['barangay'])) {
                $this->locationBase['barangay_code'] = $_GET['barangay'];
            }
        }
    }
}
