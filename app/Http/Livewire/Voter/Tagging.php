<?php

namespace App\Http\Livewire\Voter;

use App\Domains\Auth\Models\User;
use App\Domains\Household\Models\Household;
use App\Domains\Voter\Actions\TagVoterAction;
use App\Jobs\AddVoterJob;
use Illuminate\Support\Facades\Http;
use Livewire\Component;

class Tagging extends Component
{
    public $number;
    public $leader;
    public $households;
    public $household;
    public $householdNumber;
    public $householdName;
    public int $perPage = 100;
    public function searchLeader()
    {
        $user = User::where('phone', '0' . $this->number)
            ->orWhere('phone', $this->number)->first();

        $this->leader = $user->leader;

        $this->getHouseholds($user);
    }

    public function mount(){
        ini_set('memory_limit', '1G');
    }


    public function createHousehold()
    {
        $householdNumber = sprintf("%05d", $this->leader->id + ($this->householdNumber + 1));
        $this->householdName = 'P' . $this->leader->address->zone . '-' . $householdNumber;

        $response = Http::post('https://graceful-plateau-b2yvltfiyipl.vapor-farm-e1.com/api/v1/household', [
            'household_name' => $this->householdName,
            'leader_id' => $this->leader->user->id
        ]);
        $this->getHouseholds($this->leader->user);
    }

    private function getHouseholds($user)
    {
        $response = Http::get('https://graceful-plateau-b2yvltfiyipl.vapor-farm-e1.com/api/v1/households/' . $user->id);
        $this->households = $response->json()['household'];
        $this->householdNumber = $response->json()['number_household'];
        $this->emit('swal:modal', [
            'icon' => 'success',
            'title' => "Successfully loaded",
            'show_confirm_button' => true
        ]);

    }

    public function render()
    {
        return view('livewire.voters.tagging');
    }

    protected function getListeners()
    {
        return  [
            'tagVoter' => 'tagVoter',
        ];
    }

    public function tagVoter($voterId)
    {
        (new TagVoterAction)($voterId, $this->household);

        $this->emit('swal:modal', [
                    'icon' => 'success',
                    'title' => "Successfully added",
        ]);


        // $response1 = Http::post('https://graceful-plateau-b2yvltfiyipl.vapor-farm-e1.com/api/v1/add/household/voter', [
        //     'voter' => $voterId,
        //     'household' => $this->household
        // ]);

        // if (!$response1->successful()) {
        //     $this->emit('swal:modal', [
        //         'icon' => 'error',
        //         'title' => "failed to add voter",
        //     ]);
        //     return;
        // }

        // $response2 = Http::post('https://graceful-plateau-b2yvltfiyipl.vapor-farm-e1.com/api/v1/voters/set/stance', [
        //     'stance' => 'Pro',
        //     'user_id' => $voterId
        // ]);

        // if ($response2->successful() && $response1->successful()) {
        //     $this->emit('swal:modal', [
        //         'icon' => 'success',
        //         'title' => "Successfully added",
        //     ]);
        // } else {
        //     if (!$response2->successful()) {
        //         $this->emit('swal:modal', [
        //             'icon' => 'error',
        //             'title' => "failed to set stance",
        //         ]);
        //     }

        //     if (!$response1->successful()) {
        //         $this->emit('swal:modal', [
        //             'icon' => 'error',
        //             'title' => "failed to add voter",
        //         ]);
        //     }
        // }

    }
}
