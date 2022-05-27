<?php

namespace App\Http\Livewire\Analytics;

use App\Domains\Analytics\Models\TotalVotersPerLocation;
use Livewire\Component;

class AddTotalVoters extends Component
{
    public $barangayCode;
    public $totalVoter;
    public $locationBase;
    public $data;
    public $rules = [
        'totalVoter' => 'required'
    ];
    protected function getListeners()
    {
        return  [
            'setLocationToAddTotalVoter' => 'setLocationToAddTotalVoter',
        ];
    }

    public function setLocationToAddTotalVoter($data)
    {
        if($data['type'] !== 'barangay')
        {
            $this->message = 'Please search for specific barangay...';
            return;
        }

        $this->locationBase = $data['code'];
        $this->data = $data;
    }

    public function add()
    {
        $this->validate();
        $barangay = TotalVotersPerLocation::where('barangay_code',  $this->locationBase['barangay_code'])->first();
        if($barangay)
        {
            $barangay->total_voters = $this->totalVoter;
            $barangay->save();
            $this->emit('setLocation', $this->data);
        }

        TotalVotersPerLocation::updateOrCreate([
            'total_voters' => $this->totalVoter,
            'barangay_code' =>  $this->locationBase['barangay_code'],
            'city_code' =>  $this->locationBase['city_code'],
            'province_code' => $this->locationBase['province_code'],
            'region_code' => $this->locationBase['region_code'],
        ]);

        $this->emit('setLocation', $this->data);
    }
    public function render()
    {
        return view('livewire.analytics.add-total-voters');
    }
}
