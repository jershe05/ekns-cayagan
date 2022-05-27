<?php

namespace App\Http\Livewire\Leader;

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

class AddGeographicalLeader extends Component
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
    public $organizations;
    public $organizationName;

    public $isRegionDisabled = true;
    public $isProvinceDisabled = true;
    public $isCityDisabled = true;
    public $isBarangayDisabled = true;
    public $alertMessage;
    public $alertType;

    public $selectedOrganization;
    public $createdOrganization;
    public $locationBase = [
        'island_id' => null,
        'region_code' => null,
        'province_code' => null,
        'city_code' => null,
        'barangay_code' => null
    ];


    public function mount()
    {
        $this->geographicalLocationBase = $this->locationBase;
        $this->scopes = Scope::where('id', '<', 4)->get();
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

        $this->locationBase['island_id'] = $this->scope;

        $this->isRegionDisabled = false;
        $this->leaderLabel = Scope::where('id', $this->scope)->first()->name . ' Leaders';
        $this->regions = Region::join('island_regions', 'island_regions.region_code', '=', 'regions.region_code')
            ->where('island_regions.scope_id', $this->scope)
            ->get();

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

        $this->showLeaders();
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

        $this->showLeaders();

        $this->leaderLabel = Region::where('region_code', $this->region)->first()->region_description . ' Leaders' ;
        $this->isCityDisabled = false;
        $this->isBarangayDisabled = true;
        $this->cities = City::where('province_code', $this->province)->get();

    }

    public function showBarangays()
    {
        if(!$this->city)
        {
            $this->resetOnSelectCity();
            $this->showCities();
            return;
        }

        $this->locationBase['province_code'] = $this->province;
        $this->showLeaders();

        $this->isBarangayDisabled = false;
        $this->barangays = Barangay::where('city_municipality_code', $this->city)->get();
    }

    public function selectBarangay()
    {
        if(!$this->barangay)
        {
            $this->locationBase['city_code'] = null;
            $this->showBarangays();
            return;
        }

        $this->locationBase['city_code'] = $this->city;
        $this->leaderLabel = City::where('city_municipality_code', $this->city)->first()->city_municipality_description . ' Leaders' ;
        $this->showLeaders();
    }

    public function showOrganization()
    {
        $this->organizations = $this->getOrganizations();
    }

    public function getOrganizations($name = null)
    {
        $organizations = Organization::query();
        $organizations->join('addresses', 'addresses.addressable_id', '=', 'organizations.id')
            ->where('addresses.addressable_type', Organization::class);

        foreach($this->locationBase as $key => $location)
        {
            $organizations->where('addresses.' . $key, $location);
        }

        if($name)
        {
            $organizations->where('organizations.name', $name);
        }

        $organizations->select('organizations.*');

        return $organizations->distinct()->get();
    }

    public function addOrganization()
    {
        if($this->checkDuplicateOrganization($this->organizationName))
        {
            return;
        }

        $organization = Organization::create([
            'name' => $this->organizationName,
        ]);

        (new StoreModelAddressAction)(
                new AddressData([
                    'scope_id' => $this->scope,
                    'region_code' => $this->region,
                    'province_code' => $this->province,
                    'city_code' => $this->city,
                    'barangay_code' => $this->barangay
                 ]),
            $organization
        );

        $this->alertType="alert-success";
        $this->alertMessage = "Added new Organization";
        $this->createdOrganization = $organization;
        $this->selectedOrganization = $organization->id;

        $this->showLeaders(true);
    }

    public function checkDuplicateOrganization($name)
    {
        if(count($this->getOrganizations($name)) > 0)
        {
            $this->alertType="alert-danger";
            $this->alertMessage = "Duplicate Organization, please enter others names or select other scopes.";

            return true;
        }

        return false;
    }

    public function showLeaders($isSelectGeographicalLeaders = false)
    {

        if(!$this->region)
        {
            return;
        }

        $leaders = User::query();

        $leaders->join('leaders', 'leaders.user_id', '=', 'users.id')
            ->join('addresses', 'addresses.addressable_id', '=', 'leaders.id')
            ->whereNull('leaders.organization_id')
            ->where('addresses.addressable_type', Leader::class);

        foreach($this->locationBase as $key => $location)
        {
            $leaders->where('addresses.' . $key, $location);
        }

        $this->leaders = $leaders->select('users.*', 'leaders.*' )->get();

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
        $this->locationBase['island_id'] = null;
    }

    private function resetOnSelectProvince()
    {
        $this->leaders = null;
        $this->locationBase['region_code'] = null;
        $this->locationBase['province_code'] = null;
        $this->locationBase['city_code'] = null;
    }

    private function resetOnSelectCity()
    {
        $this->locationBase['province_code'] = null;
        $this->locationBase['city_code'] = null;
        $this->leaders = null;
    }

    public function render()
    {
        return view('livewire.leader.add-geographical-leader');
    }
}
