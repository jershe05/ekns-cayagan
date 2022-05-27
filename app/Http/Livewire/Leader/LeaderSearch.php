<?php

namespace App\Http\Livewire\Leader;

use App\Domains\Auth\Models\User;
use App\Domains\Leader\Models\Leader;
use App\Domains\Misc\Models\Barangay;
use App\Domains\Misc\Models\City;
use App\Domains\Misc\Models\Province;
use App\Domains\Misc\Models\Region;
use App\Domains\Scope\Models\Scope;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Livewire\Component;
use Maatwebsite\Excel\Concerns\ToArray;
use Prophecy\Prophet;

class LeaderSearch extends Component
{

    public $term;
    public $searchResults = array();
    public $locationBase;
    public $isHidden;

    protected function getListeners()
    {
        return  [
            'setVoterLocation' => 'setVoterLocation',
        ];
    }

    public function setVoterLocation($data)
    {
        $this->locationBase = $data;
        $this->firstLoad = false;
    }

    public function search()
    {
        if (strlen($this->term) > 2 && $this->locationBase !== null) {
            $this->searchResults = array();
            $term = $this->term;
            $voters = User::query();
            $voters->join('addresses', 'addresses.addressable_id', 'users.id')
                ->where('addresses.addressable_type', User::class)
                ->where(DB::raw("CONCAT(users.first_name, ' ', users.middle_name , ' ', users.last_name)"),
                    'LIKE', "%". $this->term . "%");

            
            foreach($this->locationBase as $key => $location)
            {
                if ($location && $key !== 'zone') {
                    $voters->where('addresses.' . $key,  $location);
                }
            }

           $voters = $voters->limit(5)->get();
            foreach ($voters as $voter) {
                $leader = Leader::where('user_id', $voter->addressable_id)->first();
                if(!$leader) {
                    array_push($this->searchResults, [
                        'name' => $voter->first_name . ' ' . $voter->middle_name . ' ' . $voter->last_name,
                        'user_id' => $voter->addressable_id]);
                }
            }
        }
    }

    public function select($userId)
    {
        $this->emit('showSelectedLeader', ['user' => $userId,
        'location' => $this->locationBase]);
        $this->term = null;
    }

    public function render()
    {
        return view('livewire.leader.leader-search');
    }
}
