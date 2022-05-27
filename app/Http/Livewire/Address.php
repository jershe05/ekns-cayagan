<?php

namespace App\Http\Livewire;

use App\Domains\Misc\Models\Barangay;
use App\Domains\Misc\Models\City;
use App\Domains\Misc\Models\Province;
use App\Domains\Misc\Models\Region;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Illuminate\Http\Request;
class Address extends Component
{
    public $regions;
    public $region;
    public $province;
    public $provinces;
    public $city;
    public $cities;
    public $barangay;
    public $barangays;

    public $provinceIsDisabled = true;
    public $cityIsDisabled = true;
    public $barangayIsDisabled = true;

    public $selectedRegion;
    public $selectedProvince;
    public $selectedCity;
    public $selectedBarangay;
    public $isTotalVoter;

    public function mount(Request $request, $isTotalVoter = false)
    {

        $this->getSelectedLocation($request->all());
        $this->regions = Region::all();
        $this->isTotalVoter = $isTotalVoter;
    }

    private function getSelectedLocation($scope)
    {
        if(array_key_exists('region_code', $scope))
        {
            $this->selectedRegion = Region::where('region_code', $scope['region_code'])->first();

        }

        if(array_key_exists('province_code', $scope))
        {
            $this->selectedProvince = Province::where('province_code', $scope['province_code'])->first();
            $this->selectedRegion = Region::where('region_code', $this->selectedProvince->region_code)->first();
            $this->provinces = Province::where('region_code', $this->selectedProvince->region_code)->get();
            $this->provinceIsDisabled = false;
        }

        if(array_key_exists('city_code', $scope))
        {
            $this->selectedCity = City::where('city_municipality_code', $scope['city_code'])->first();
            $this->selectedProvince = Province::where('province_code', $this->selectedCity->province_code)->first();
            $this->selectedRegion = Region::where('region_code', $this->selectedCity->region_description)->first();
            $this->provinces = Province::where('region_code', $this->selectedCity->region_description)->get();
            $this->cities = City::where('province_code', $this->selectedCity->province_code)->get();
            $this->provinceIsDisabled = false;
            $this->cityIsDisabled = false;
        }

    }

    public function showProvinces()
    {
        if(!$this->region)
        {
            $this->regions = Region::all();
            $this->emit('setLocation',  ['code' => 'national', 'value' => null]);
            return;
        }

        if($this->isTotalVoter)
        {
            $this->emit('setLocation',  ['code' => 'region_code', 'value' => $this->region]);
        }

        $this->provinceIsDisabled = false;
        $this->cityIsDisabled = true;
        $this->barangayIsDisabled = true;
        $this->cities = null;
        $this->barangays = null;
        $this->city = null;
        $this->barangay = null;

        $this->provinces = Province::where('region_code', $this->region)->get();
    }

    public function showCities()
    {
        if(!$this->province)
        {
            $this->showProvinces();
            return;
        }

        if($this->isTotalVoter)
        {
            $this->emit('setLocation', ['code' => 'province_code', 'value' => $this->province]);
        }

        $this->cityIsDisabled = false;
        $this->barangays = null;
        $this->barangayIsDisabled = true;
        $this->barangay = null;
        $this->cities = City::where('province_code', $this->province)->get();
    }

    public function showBarangays()
    {
        if(!$this->city)
        {
            $this->showCities();
            return;
        }

        if($this->isTotalVoter)
        {
            $this->emit('setLocation', ['code' => 'city_code', 'value' => $this->city]);
        }

        $this->barangayIsDisabled = false;
        $this->barangays = Barangay::where('city_municipality_code', $this->city)->get();
    }

    public function render()
    {
        return view('livewire.address');
    }
}
