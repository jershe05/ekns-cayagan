<?php

namespace App\Http\Livewire;

use App\Domains\Organization\Models\Organization as ModelsOrganization;
use Livewire\Component;

class Organization extends Component
{
    public $island;
    public $region;
    public $province;
    public $city;
    public $barangay;
    public $organizations;

    public function mount($island = null, $region = null, $province = null, $city = null, $barangay = null)
    {
        $this->island = $island;
        $this->region = $region;
        $this->province = $province;
        $this->city = $city;
        $this->barangay = $barangay;


        $this->organizations = ModelsOrganization::join('addresses', 'addresses.addressable_id', '=', 'organizations.id')
            ->where('addresses.island_id', $this->island)
            ->where('addresses.region_code', $this->region)
            ->where('addresses.province_code', $this->province)
            ->where('addresses.city_code', $this->city)
            ->where('addresses.barangay_code', $this->barangay)
            ->get();

        dd($this->island);
    }

    public function render()
    {
        return view('livewire.organization');
    }
}
