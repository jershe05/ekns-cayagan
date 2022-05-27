<?php

namespace App\Http\Livewire\Leader;

use App\Domains\Auth\Models\User;
use App\Domains\Leader\Models\Leader;
use App\Domains\Misc\Actions\StoreModelAddressAction;
use App\Domains\Misc\DataTransferObjects\AddressData;
use App\Domains\Misc\Models\Barangay;
use App\Domains\Misc\Models\City;
use App\Domains\Misc\Models\Province;
use App\Domains\Misc\Models\Region;
use App\Domains\Organization\Models\Organization;
use App\Domains\Scope\Models\Scope;
use App\Http\Livewire\Traits\Island;
use Livewire\Component;
use PhpParser\Node\Expr\NullsafeMethodCall;

class AddLeader extends Component
{

    public $isSearchLeaderHidden = true;
    public $userId;
    public $firstName;
    public $middleName;
    public $lastName;
    public $gender;
    public $email;
    public $phone;
    public $birthday;
    public $address;
    public $region_description;
    public $region;
    public $province_description;
    public $province;
    public $city_description;
    public $city;
    public $barangay_description;
    public $barangay;
    public $purok;
    public $showData = false;
    

    public function mount()
    {
        // $this->geographicalLocationBase = $this->sectorLocationBase;
        // $this->scopes = Scope::where('id', '<', 4)->get();
    }

    protected function getListeners()
    {
        return  [
            'showSelectedLeader' => 'showSelectedLeader',
            'resetSelectedleader' => 'resetSelectedleader'
        ];
    }

    public function resetSelectedleader()
    {
        $this->user_id = null;
        $this->firstName = null;
        $this->middleName = null;
        $this->lastName = null;
        $this->gender = null;
        $this->email = null;
        $this->phone = null;
        $this->birthday = null;
        $this->address = null;
        $this->showData = false;
    }


    public function showSelectedLeader($data)
    {
        $leader = User::find($data['user']);
        $this->userId = $leader->id;
        $this->firstName = $leader->first_name;
        $this->middleName = $leader->middle_name;
        $this->lastName = $leader->last_name;
        $this->gender = $leader->gender;
        $this->email = $leader->email;
        $this->phone = $leader->phone;
        $this->birthday = $leader->birthday;
        $this->address = $leader->address->zone . ' ' . $leader->address->barangay->barangay_description
            . ' ' . $leader->address->city->city_municipality_description . ' ' . $leader->address->province->province_description
            . ' ' . $leader->address->region->region_description;

        $this->region = $data['location']['region_code'];
        if ($this->region) {
            $this->region_description = Region::where('region_code', $this->region)->first()->region_description;
        }

        $this->province = $data['location']['province_code'];
        if ($this->province) {
            $this->province_description = Province::where('province_code', $this->province)->first()->province_description;
        }

        $this->city = $data['location']['city_code'];
        if ($this->city) {
            $this->city_description = City::where('city_municipality_code',  $this->city)->first()->city_municipality_description;
        }

        $this->barangay = $data['location']['barangay_code'];
        if ($this->barangay) {
            $this->barangay_description = Barangay::where('barangay_code', $this->barangay)->first()->barangay_description;
        }
        $this->purok = $data['location']['zone'];
        $this->showData = true;
     }

    public function render()
    {
        return view('livewire.leader.add-leader');
    }
}
