<?php

namespace App\Http\Livewire;

use App\Domains\Analytics\Actions\PercentagePerLocation;
use App\Domains\Misc\Models\City;
use Livewire\Component;

class CityListTable extends Component
{
    public $cities;
    public $cityList;
    public function mount(PercentagePerLocation $percentagePerLocation, $provinceCode)
    {
        $this->cities = City::where('province_code', $provinceCode)
            ->get();

        foreach($this->cities as $key => $city)
        {
            $this->cityList[$key] = [
                'city_description' => $city->city_municipality_description,
                'city_code' => $city->city_municipality_code,
                'status' => $percentagePerLocation->getCityPercentage($city->city_municipality_code)
            ];
        }

    }

    public function loadBarangay($cityCode)
    {
        $this->emit('loadBarangay', $cityCode);
    }

    public function render()
    {
        return view('livewire.city-list-table');
    }
}
