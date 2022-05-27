<?php

namespace App\Http\Livewire;

use App\Domains\Analytics\Actions\PercentagePerLocation;
use App\Domains\Misc\Models\Region;
use Livewire\Component;

class RegionListTable extends Component
{
    public $regions;
    public $regionList;
    public function mount(PercentagePerLocation $percentagePerLocation, $islandId)
    {
        $this->regions = Region::join('island_regions', 'island_regions.region_code', 'regions.region_code')
            ->where('island_regions.scope_id', $islandId)
            ->get();

        foreach($this->regions as $key => $region)
        {
            $this->regionList[$key] = [
                'region_description' => $region->region_description,
                'region_code' => $region->region_code,
                'status' => $percentagePerLocation->getProvincePercentage($region->region_code)
            ];
        }

    }

    public function loadProvince($regionCode)
    {
        $this->emit('loadProvince', $regionCode);
    }

    public function render()
    {
        return view('livewire.region-list-table');
    }
}
