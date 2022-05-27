<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Domains\Scope\Models\Scope;
use App\Domains\Auth\Models\User;
use App\Domains\Leader\Models\Leader;
use App\Domains\Misc\Actions\StoreModelAddressAction;
use App\Domains\Misc\DataTransferObjects\AddressData;
use App\Domains\Misc\Models\Barangay;
use App\Domains\Misc\Models\City;
use App\Domains\Misc\Models\Province;
use App\Domains\Misc\Models\Region;
use App\Domains\Organization\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SearchByAddress extends Component
{
    public $scopes;
    public $scope;
    public $islandId;
    public $region_code;
    public $province_code;
    public $city_code;
    public $barangay_code;

    public $regions;
    public $region;
    public $provinces;
    public $province;
    public $cities;
    public $city;
    public $barangays;
    public $barangay;
    public $leaderLabel;
    public $leaders;

    public $isRegionDisabled = true;
    public $isProvinceDisabled = true;
    public $isCityDisabled = false;
    public $isBarangayDisabled = true;

    public $locationLevel;

    public $locationBase = [
        'island_id' => null,
        'region_code' => null,
        'province_code' => null,
        'city_code' => null,
        'barangay_code' => null
    ];

    public $event;

    public function mount($event)
    {
        $this->event = $event;
        $this->scopes = Scope::where('id', '<', 4)->get();
        $this->selectLocationLevel();
        if($this->locationLevel === 'provincial') {
            $this->showCities();
        } else if($this->locationLevel === 'city') {
            $this->isBarangayDisabled = false;
            $this->showBarangays();
        }
    }

    public function selectLocationLevel()
    {
        if(auth()->user()->address->province_code) {
            $this->locationLevel = 'provincial';
            $this->province_code = auth()->user()->address->province_code;
            $this->isCityDisabled = false;
            if (isset($_GET['city'])) {
                $this->city_code = $_GET['city'];
            }

            if (isset($_GET['barangay'])) {
                $this->barangay_code = $_GET['barangay'];
            }
        } else if(auth()->user()->address->city_code) {
            $this->locationLevel = 'city';
            $this->city_code = auth()->user()->address->city_code;
            if (isset($_GET['barangay'])) {
                $this->barangay_code = $_GET['barangay'];
            }
        }

    }

    public function selectScopeType()
    {
        $this->resetData();
    }

    public function showRegions()
    {
        $this->resetOnSelectIsland();
        $this->locationBase['island_id'] = $this->islandId;
        $this->emit($this->event, $this->locationBase);
        $this->regions();
    }

    public function showProvinces()
    {
        $this->resetOnSelectRegion();
        $this->locationBase['region_code'] = $this->region_code;
        $this->emit($this->event, $this->locationBase);
        $this->provinces();
    }

    public function showCities()
    {
        $this->resetOnSelectProvince();
        $this->locationBase['province_code'] = $this->province_code;
        $province = Province::where('province_code', $this->province_code)->first();
        $this->scope = $province->province_description;
        $this->emit($this->event, [
            'type' => 'province',
            'code' => [
                'region_code' => $province->region_description,
                'province_code' => $province->province_code,
                'city_code' => null,
                'barangay_code' => null
            ]
        ]);

        $this->cities();
    }

    public function showBarangays()
    {
        $this->resetOnSelectCity();
        $this->locationBase['city_code'] = $this->city_code;
        $city = City::where('city_municipality_code', $this->city_code)->first();
        if ($city) {
            $this->scope = $city->city_municipality_description;
            $this->emit($this->event, [
                'type' => 'city',
                'code' => [
                    'region_code' => $city->region_description,
                    'province_code' => $city->province_code,
                    'city_code' => $city->city_municipality_code,
                    'barangay_code' => null
                ]
            ]);
            $this->barangays();
            return;
        }

        $this->isBarangayDisabled = true;
        $this->emit($this->event, [
            'type' => 'city',
            'code' => [
                'city_code' => null,
                'barangay_code' => null
            ]
        ]);
    }

    public function selectBarangay()
    {
        $barangay = Barangay::where('barangay_code', $this->barangay_code)->first();
        if ($barangay) {
            $this->scope = $barangay->barangay_description;
            $this->emit($this->event, [
                'type' => 'barangay',
                'code' => [
                    'region_code' => $barangay->region_code,
                    'province_code' => $barangay->province_code,
                    'city_code' => $barangay->city_municipality_code,
                    'barangay_code' => $barangay->barangay_code
                ]
            ]);
            return;
        }
        $this->showBarangays();
    }

    private function regions()
    {
        if(!$this->locationBase['island_id'])
        {
            $this->resetOnSelectIsland();
            return;
        }
        $this->scope = Scope::where('id', $this->locationBase['island_id'])->first();
        $this->isRegionDisabled = false;
        $this->regions = Region::join('island_regions', 'island_regions.region_code', '=', 'regions.region_code')
            ->where('island_regions.scope_id', $this->locationBase['island_id'])
            ->get();
        $this->region = Region::where('region_code', $this->locationBase['region_code'])->first();
        $this->isProvinceDisabled = true;
        $this->isCityDisabled = true;
        $this->isBarangayDisabled = true;
    }

    private function provinces()
    {
        if(!$this->locationBase['region_code'])
        {
            return;
        }
        $this->provinces = Province::where('region_code', $this->locationBase['region_code'])->get();
        $this->province = Province::where('province_code', $this->locationBase['province_code'])->first();

        $this->selectedOrganization = false;
        $this->isSelectOrganizationHidden = false;
        $this->isProvinceDisabled = false;

    }

    private function cities()
    {
        if(!$this->locationBase['province_code'])
        {
            return;
        }

        $this->cities = City::where('province_code', $this->locationBase['province_code'])->get();
        $this->city = City::where('city_municipality_code', $this->locationBase['city_code'])->first();
        $this->isCityDisabled = false;
        $this->isBarangayDisabled = true;


    }

    private function barangays()
    {

        if($this->locationLevel !== 'city') {
            if(!$this->locationBase['city_code'])
            {
                return;
            }
        }

        $this->isBarangayDisabled = false;
        $this->barangays = Barangay::where('city_municipality_code',  $this->locationBase['city_code'])->get();
        $this->barangay = Barangay::where('barangay_code', $this->locationBase['barangay_code'])->first();
    }



    private function resetOnSelectIsland()
    {
        $this->locationBase['island_id'] = null;
        $this->locationBase['region_code'] = null;
        $this->locationBase['province_code'] = null;
        $this->locationBase['city_code'] = null;
        $this->locationBase['barangay_code'] = null;
        $this->scopes = Scope::where('id', '<', 4)->get();

    }

    private function resetOnSelectRegion()
    {

        $this->locationBase['region_code'] = null;
        $this->locationBase['province_code'] = null;
        $this->locationBase['city_code'] = null;
        $this->locationBase['barangay_code'] = null;
    }

    private function resetOnSelectProvince()
    {
        $this->locationBase['province_code'] = null;
        $this->locationBase['city_code'] = null;
        $this->locationBase['barangay_code'] = null;
    }

    private function resetOnSelectCity()
    {
        $this->locationBase['city_code'] = null;
        $this->locationBase['barangay_code'] = null;
    }

    public function render()
    {
        return view('livewire.search-by-address');
    }
}
