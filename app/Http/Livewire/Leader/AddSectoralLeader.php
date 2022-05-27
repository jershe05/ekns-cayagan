<?php

namespace App\Http\Livewire\Leader;

use App\Domains\Scope\Models\Scope;
use Livewire\Component;
use App\Domains\Auth\Models\User;
use App\Domains\Leader\Models\Leader;
use App\Domains\Misc\Actions\StoreModelAddressAction;
use App\Domains\Misc\DataTransferObjects\AddressData;
use App\Domains\Misc\Models\Barangay;
use App\Domains\Misc\Models\City;
use App\Domains\Misc\Models\Province;
use App\Domains\Misc\Models\Region;
use App\Domains\Organization\Models\Organization;
use Illuminate\Support\Facades\Session;

class AddSectoralLeader extends Component
{
    public $scopes;
    public $scope;
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
    public $scopeType;
    public $isSelectOrganizationHidden = true;
    public $organizations;
    public $organizationName;

    public $isRegionDisabled = true;
    public $isProvinceDisabled = true;
    public $isCityDisabled = true;
    public $isBarangayDisabled = true;
    public $isSelectPurokDisabled = true;
    public $alertMessage;
    public $alertType;

    public $zone = 1;
    public $purokLeader = false;

    public $selectedOrganization;
    public $createdOrganization;
    public $locationBase = [
        'island_id' => null,
        'region_code' => null,
        'province_code' => null,
        'city_code' => null,
        'barangay_code' => null,
        'zone' => null
    ];


    public $geographicalLocationBase;
    public $showSelectPurok = false;
    public $locationLevel;

    public function mount()
    {
        $this->geographicalLocationBase = $this->locationBase;
        $this->scopes = Scope::where('id', '<', 4)->get();

        $this->province = '0215';
        $this->selectLocationLevel();
        if($this->locationLevel === 'provincial') {
            $this->showCities();
        } else if($this->locationLevel === 'city') {
            $this->isBarangayDisabled = false;
            $this->showBarangays();
        }
    }

    public function setVoterLocation()
    {
        $this->emit('setVoterLocation', $this->geographicalLocationBase);
    }

    public function selectLocationLevel()
    {
        if(auth()->user()->address->province_code) {
            $this->locationLevel = 'provincial';
            $this->province = auth()->user()->address->province_code;
            $this->isCityDisabled = false;
        } else if(auth()->user()->address->city_code) {
            $this->locationLevel = 'city';
            $this->city = auth()->user()->address->city_code;
        }

    }

    public function selectScopeType()
    {
        $this->resetData();
    }

    public function showRegions()
    {
        if(!$this->scope)
        {
            $this->resetsOnSelectIsland();
            return;
        }
        $this->geographicalLocationBase['island_id'] =  $this->scope;
        $this->locationBase['island_id'] = $this->scope;

        $this->isSelectOrganizationHidden = false;

        $this->isRegionDisabled = false;
        $this->leaderLabel = Scope::where('id', $this->scope)->first()->name . ' Leaders';
        $this->regions = Region::join('island_regions', 'island_regions.region_code', '=', 'regions.region_code')
            ->where('island_regions.scope_id', $this->scope)
            ->get();

        $this->setVoterLocation();
        $this->isProvinceDisabled = true;
        $this->isCityDisabled = true;
        $this->isBarangayDisabled = true;

    }

    public function showProvinces()
    {
        if(!$this->region)
        {
            $this->resetOnSelectRegion();
            $this->showRegions();
            return;
        }

        $this->locationBase['island_id'] = $this->scope;
        $this->geographicalLocationBase['region_code'] = $this->region;
        $this->setVoterLocation();
        $this->selectedOrganization = false;
        $this->isSelectOrganizationHidden = false;
        $this->isProvinceDisabled = false;
        $this->provinces = Province::where('region_code', $this->region)->get();
    }

    public function showCities()
    {
        if(!$this->province)
        {
            $this->resetOnSelectProvince();
            $this->showProvinces();
            return;
        }


        $this->locationBase['region_code'] = $this->region;
        $this->geographicalLocationBase['province_code'] = $this->province;

        $this->selectedOrganization = false;
        $this->isSelectOrganizationHidden = false;

        $this->setVoterLocation();
        // $this->leaderLabel = Region::where('region_code', $this->region)->first()->region_description . ' Leaders' ;
        $this->isCityDisabled = false;
        $this->isBarangayDisabled = true;
        $this->cities = City::where('province_code', '0215')->get();

    }

    public function showBarangays()
    {
        if($this->locationLevel !== 'city')
        {
            if(!$this->city)
            {
                $this->resetOnSelectCity();
                $this->showCities();
                return;
            }
        }

        $this->isBarangayDisabled = false;
        $this->locationBase['province_code'] = $this->province;
        $this->geographicalLocationBase['city_code'] = $this->city;
        $this->selectedOrganization = false;
        $this->isSelectOrganizationHidden = false;
        $this->barangays = Barangay::where('city_municipality_code', $this->city)->get();
        $this->setVoterLocation();
    }

    public function selectBarangay()
    {
        if(!$this->barangay)
        {
            $this->locationBase['city_code'] = null;
            $this->geographicalLocationBase['barangay_code'] = null;
            $this->showBarangays();
            return;
        }

        $this->locationBase['city_code'] = $this->city;
        $this->geographicalLocationBase['barangay_code'] = $this->barangay;
        $this->leaderLabel = City::where('city_municipality_code', $this->city)->first()->city_municipality_description . ' Leaders' ;

        $this->thereIsLeader = Leader::join('addresses', 'addresses.addressable_id', '=', 'leaders.id')
            ->where('addresses.addressable_type', Leader::class)
            ->where('leaders.organization_id', null)
            ->where('addresses.barangay_code', $this->barangay)->count();

        $this->isSelectPurokDisabled = false;

        $this->setVoterLocation();
    }


    private function resetsOnSelectIsland()
    {
        $this->locationBase['island_id'] = null;
        $this->scopes = null;
        $this->scope = null;
        $this->regions = null;
        $this->region = null;
        $this->provinces = null;
        $this->province = null;
        $this->cities = null;
        $this->city = null;
        $this->barangays = null;
        $this->barangay = null;
        $this->leaderLabel = null;
        $this->leaders = null;
        $this->isSelectOrganizationHidden = true;
        $this->organizations = null;
        $this->organizationName = null;

        $this->isRegionDisabled = true;
        $this->isProvinceDisabled = true;
        $this->isCityDisabled = true;
        $this->isBarangayDisabled = true;
        $this->alertMessage = null;
        $this->alertType = null;
        $this->selectedOrganization = null;

        $this->scopes = Scope::where('id', '<', 4)->get();
    }

    private function resetOnSelectRegion()
    {
        $this->leaders = null;
        $this->provinces = null;
        $this->leaderLabel = null;
        $this->selectedOrganization = null;
        $this->isSelectOrganizationHidden = true;
        $this->thereIsLeaderOnThisRegion = false;
        $this->locationBase['island_id'] = null;
        $this->geographicalLocationBase['region_code'] = null;
    }

    private function resetOnSelectProvince()
    {
        $this->leaders = null;
        $this->isSelectOrganizationHidden = true;
        $this->thereIsLeaderOnThisRegion = false;
        $this->locationBase['region_code'] = null;
        $this->locationBase['province_code'] = null;
        $this->locationBase['city_code'] = null;

        $this->geographicalLocationBase['province_code'] = null;
        $this->geographicalLocationBase['city_code'] = null;
        $this->geographicalLocationBase['barangay_code'] = null;
    }

    private function resetOnSelectCity()
    {
        $this->locationBase['province_code'] = null;
        $this->locationBase['city_code'] = null;
        $this->geographicalLocationBase['city_code'] = null;
        $this->geographicalLocationBase['barangay_code'] = null;
    }

    public function render()
    {
        return view('livewire.leader.add-sectoral-leader');
    }

    public function showPurokInput()
    {
        $this->resetSelectedleader();
        if($this->purokLeader) {
            $this->showSelectPurok = true;
            $this->geographicalLocationBase['zone'] = $this->zone;
        } else {
            $this->geographicalLocationBase['zone'] = null;
            $this->zone = 1;
            $this->showSelectPurok = false;
        }
        $this->setVoterLocation();
    }

    public function zoneOnChange()
    {
        $this->resetSelectedleader();
        $this->geographicalLocationBase['zone'] = $this->zone;
        $this->setVoterLocation();
    }

    public function resetSelectedleader()
    {
        $this->emit('resetSelectedleader');
    }
}
