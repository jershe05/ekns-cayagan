<?php

namespace App\Http\Livewire;

use App\Domains\Analytics\Actions\PercentagePerLocation;
use App\Domains\Misc\Models\Province;
use Livewire\Component;

class ProvinceListTable extends Component
{

    public $provinces;
    public $provinceList;
    public function mount(PercentagePerLocation $percentagePerLocation, $regionCode)
    {
        $this->provinces = Province::where('region_code', $regionCode)
            ->get();

        foreach($this->provinces as $key => $province)
        {
            $this->provinceList[$key] = [
                'province_description' => $province->province_description,
                'province_code' => $province->province_code,
                'status' => $percentagePerLocation->getCityPercentage($province->province_code)
            ];
        }
    
    }

    public function loadCity($cityCode)
    {
        $this->emit('loadCity', $cityCode);
    }
    public function render()
    {
        return view('livewire.province-list-table');
    }
}
