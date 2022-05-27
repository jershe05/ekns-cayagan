<?php

namespace App\Http\Livewire;

use App\Domains\Analytics\Actions\PercentagePerLocation;
use App\Domains\Misc\Models\Barangay;
use Livewire\Component;

class BarangayListTable extends Component
{
    public $barangays;
    public $barangayList;
    public function mount(PercentagePerLocation $percentagePerLocation, $cityCode)
    {
        $this->barangays = Barangay::where('city_municipality_code', $cityCode)
            ->get();

        foreach($this->barangays as $key => $barangay)
        {
            $this->barangayList[$key] = [
                'barangay_description' => $barangay->barangay_description,
                'barangay_code' => $barangay->barangay_code,
                'city_municipality_code' => $barangay->city_municipality_code,
                'status' => $percentagePerLocation->getBarangayPercentage($barangay->barangay_code)
            ];
        }

    }

    public function render()
    {
        return view('livewire.barangay-list-table');
    }
}
