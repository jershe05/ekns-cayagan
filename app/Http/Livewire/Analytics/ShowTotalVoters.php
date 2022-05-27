<?php

namespace App\Http\Livewire\Analytics;

use Livewire\Component;

class ShowTotalVoters extends Component
{
    public $locationCode = "national";
    public $locationValue;
    public $currentTable;

    protected function getListeners()
    {
        return  [
            'setLocation' => 'setLocation',
        ];
    }

    public function setLocation($data)
    {
        $this->locationCode = $data['type'];
        $this->locationValue =  $data['code'];
    }

    public function render()
    {
        return view('livewire.analytics.show-total-voters');
    }
}
