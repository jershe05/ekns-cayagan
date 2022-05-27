<?php

namespace App\Http\Livewire;

use App\Domains\Misc\Models\Barangay;
use App\Domains\Misc\Models\City;
use App\Domains\Misc\Models\Province;
use App\Domains\Misc\Models\Region;
use App\Domains\Scope\Models\Scope;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Prophecy\Prophet;

class LocationSearch extends Component
{
    public $scope;
    public $term;
    public $searchResults;
    public $regionClass = Region::class;
    public $editLeader;
    public $event = 'markLeaderLocation';
    public $locationLevel = 'city';
    public $selectedProvinceCode;
    public $selectedCityCode;

    public function mount($event = null)
    {
        if($event)
        {
            $this->event = $event;
        }
        $this->selectLocationLevel();
    }

    public function selectLocationLevel()
    {

        if(auth()->user()->address->province_code) {
            $this->locationLevel = 'provincial';
            $this->selectedProvinceCode = auth()->user()->address->province_code;

        } else if(auth()->user()->address->city_code) {
            $this->locationLevel = 'city';
            $this->selectedCityCode = auth()->user()->address->city_code;
        }

    }

    public function search()
    {
        if(strlen($this->term) > 2)
        {

        $term = $this->term;
        $searchCollection = collect();

        if ($this->locationLevel === 'national') {
            $island = Scope::where('name', 'like', "%$term%")
            ->select('id as island_id', 'name as island_description')
            ->get();
            if(count($island))
            {
                $searchCollection = $this->mergeCollection($island, $searchCollection);
            }
        }

        if ($this->locationLevel === 'national') {
            $region = Region::where('region_description', 'like', "%$term%")
            ->select('region_code', 'region_description')->get();
            if(count($region)) {
                $searchCollection = $this->mergeCollection($region, $searchCollection);
            }
        }

        if ($this->locationLevel === 'regional') {
            $province = Province::join('regions', 'regions.region_code', 'provinces.region_code')
            ->where('province_description', 'like', "%$term%")
            ->select('province_code', 'province_description', 'region_description')->get();

            if(count($province))
            {
                $searchCollection = $this->mergeCollection($province, $searchCollection);
            }
        }

        if ($this->locationLevel === 'provincial') {
            $city = City::join('regions', 'regions.region_code', 'cities.region_description')
                ->join('provinces', 'provinces.province_code', 'cities.province_code')
                ->where('city_municipality_description', 'like', "%$term%")
                ->where('provinces.province_code', $this->selectedProvinceCode)
                ->select('city_municipality_code', 'city_municipality_description', 'regions.region_description', 'province_description')->get();

            if(count($city))
            {
                $searchCollection = $this->mergeCollection($city, $searchCollection);
            }
        }

        if ($this->locationLevel === 'city') {
            $barangay = Barangay::join('regions', 'regions.region_code', 'barangays.region_code')
            ->join('provinces', 'provinces.province_code', 'barangays.province_code')
            ->join('cities', 'cities.city_municipality_code', 'barangays.city_municipality_code')
            ->where('barangays.city_municipality_code', $this->selectedCityCode)
            ->where('barangay_description', 'like', "%$term%")
            ->select('barangay_code', 'barangay_description', 'regions.region_description', 'province_description', 'city_municipality_description')->get();
        } else {
            $barangay = Barangay::join('regions', 'regions.region_code', 'barangays.region_code')
            ->join('provinces', 'provinces.province_code', 'barangays.province_code')
            ->join('cities', 'cities.city_municipality_code', 'barangays.city_municipality_code')
            ->where('barangays.province_code', $this->selectedProvinceCode)
            ->where('barangay_description', 'like', "%$term%")
            ->select('barangay_code', 'barangay_description', 'regions.region_description', 'province_description', 'city_municipality_description')->get();
        }

        if(count($barangay))
        {
            $searchCollection = $this->mergeCollection($barangay, $searchCollection);
        }

        $this->searchResults = $searchCollection;
            return;
        }

        $this->searchResults = null;
    }
    private function setAddress($location, $type)
    {

        if($type === 'island') {
            $scope = Scope::where('id', $location)->first();
            $this->scope = $scope->name;
            $this->emit($this->event, [
                'type' => $type,
                'code' => [
                    'island_id' => $scope->id,
                    'region_code' => null,
                    'province_code' => null,
                    'city_code' => null,
                    'barangay_code' => null
                ]
            ]);
            return;
        }

        if($type === 'region') {
            $region = Region::where('region_code', $location)->first();
            $this->scope = $region->region_description;
            $this->emit($this->event, [
                'type' => $type,
                'code' => [
                    'region_code' => $region->region_code,
                    'province_code' => null,
                    'city_code' => null,
                    'barangay_code' => null
                ]
            ]);
            return;
        }

        if($type === 'province') {
            $province = Province::where('province_code', $location)->first();
            $this->scope = $province->province_description;
            $this->emit($this->event, [
                'type' => $type,
                'code' => [
                    'region_code' => $province->region_code,
                    'province_code' => $province->province_code,
                    'city_code' => null,
                    'barangay_code' => null
                ]
            ]);
            return;
        }

        if($type === 'city') {
            $city = City::where('city_municipality_code', $location)->first();
            $this->scope = $city->city_municipality_description;
            $this->emit($this->event, [
                'type' => $type,
                'code' => [
                    'region_code' => $city->region_description,
                    'province_code' => $city->province_code,
                    'city_code' => $city->city_municipality_code,
                    'barangay_code' => null
                ]
            ]);
            return;
        }

         if($type === 'barangay') {
            $barangay = Barangay::where('barangay_code', $location)->first();
            $this->scope = $barangay->barangay_description;
            $this->emit($this->event, [
                'type' => $type,
                'code' => [
                    'region_code' => $barangay->region_code,
                    'province_code' => $barangay->province_code,
                    'city_code' => $barangay->city_municipality_code,
                    'barangay_code' => $barangay->barangay_code
                ]
            ]);
            return;
        }
    }


    public function select($location, $type)
    {
        $this->term = null;

        if($this->editLeader)
        {
            $event = 'setLeaderAddress';
        }

        $this->setAddress($location, $type);

    }

    private function mergeCollection($collection, $searchCollection)
    {
        foreach($collection as $item)
        {

            $searchCollection->push($item);
        }

        return $searchCollection;
    }

    public function render()
    {
        return view('livewire.location-search');
    }
}
