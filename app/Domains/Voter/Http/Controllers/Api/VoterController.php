<?php

namespace App\Domains\Voter\Http\Controllers\Api;

use App\Domains\Auth\Http\Controllers\Api\Traits\UserMisc;
use App\Domains\Auth\Models\User;
use App\Domains\Family\Models\Family;
use App\Domains\Household\Models\Household;
use App\Http\Requests\Api\Auth\RegisterVoterRequest;
use App\Domains\Voter\Actions\StoreVoterAction;
use App\Domains\Misc\Actions\StoreModelAddressAction;
use App\Domains\Misc\Actions\UpdateModelAddressAction;
use App\Domains\Misc\Http\Resources\AddressResource;
use App\Domains\Misc\Models\Barangay;
use App\Domains\Voter\Actions\UpdateVoterAction;
use App\Domains\Voter\Http\Requests\ConfirmVoterRequest;
use App\Domains\Voter\Http\Resources\VoterResource;
use App\Domains\Voter\Models\BarangayVoterStance;
use App\Domains\Voter\Models\VoterStance;
use Cache;
use F9Web\ApiResponseHelpers;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\DB;
use Request;

class VoterController
{
    use ApiResponseHelpers;
    use UserMisc;
    public function index(User $voter)
    {
        return  $this->respondCreated([
           $this->voter($voter)
        ]);
    }

    public function store(RegisterVoterRequest $request)
    {
        $voter = (new StoreVoterAction)($request->data());
        $address = (new StoreModelAddressAction)($request->data()->address, $voter);
        Family::create([
            'user_id' => $voter->id,
            'household_id' => $request->data()->household_id
        ]);

        return  $this->respondCreated([
            'voter' => new VoterResource($voter),
            'address' => new AddressResource($address)
        ]);
    }

    public function update(RegisterVoterRequest $request, User $voter)
    {
        $voter = (new UpdateVoterAction)($request->data(), $voter);
        $address = (new UpdateModelAddressAction)($request->data()->address, $voter);

        return  $this->respondCreated([
            'voter' => new VoterResource($voter),
            'address' => new AddressResource($address)
        ]);
    }

    public function confirm(ConfirmVoterRequest $request)
    {
        $voter = User::where('id', $request->voter_id)->first();
        $voter->added_by = $request->leader_id;
        $voter->save();
        $voter->refresh();

        return  $this->respondCreated([
            'success' => 'true',
        ]);
    }

    public function delete(User $voter)
    {
        $voter->delete();

        return  $this->respondCreated([
            'voter' => new VoterResource($voter),
            'address' => $this->address($voter)
        ]);
    }

    public function listOfVotersFromBarangay(Barangay $barangay, $keyword)
    {
        $votersList = collect();
        $addresses = $barangay->addresses->where('addressable_type', User::class)->count();
        // $barangayCode = $barangay->barangay_code;

        // $totalBarangayVotersCount = Cache::remember('search_voter_' . $barangayCode, 86400, function () use ($barangayCode) {
        //     $query = User::query();
        //     return $query->join('addresses', 'addresses.addressable_id', 'users.id')
        //         ->where('addresses.addressable_type', User::class)
        //         ->where('addresses.barangay_code', $barangayCode)->get();
        // });


        // $filtered = $totalBarangayVotersCount->filter(function($item) use ($keyword) {
        //     return stripos($item['first_name'] . ' ' . $item['last_name'], $keyword) !== false;
        // });

        $query = User::query();
        $results = $query->select('addresses.addressable_id', 'gender', 'email', 'first_name', 'middle_name', 'last_name', 'phone', 'precinct_id')
            ->join('addresses', 'addresses.addressable_id', 'users.id')
            ->where('addresses.addressable_type', User::class)
            ->where('addresses.barangay_code', $barangay->barangay_code)
            ->where(function ($query) use ($keyword) {
            $query->where(DB::raw("Concat(first_name, ' ', last_name)"), 'like', $keyword.'%');
        })->limit(10)->get();

        foreach($results as $user)
        {
            if (isset($user)) {
                $taggedVoter = Family::where('user_id', $user->addressable_id)->first();
                if (!$taggedVoter) {
                    $votersList->push($this->voter($user, true));
                }
            }
        }

        return $this->respondWithSuccess([
            'number_voters' => $addresses,
            'voters' => $votersList
        ]);
    }

    public function list(Household $household)
    {

        $votersList = collect();

        foreach($household->families as $family)
        {
            $votersList->push($this->voter(User::find($family->user_id)));
        }

        return $this->respondWithSuccess([
            'number_voters' => count($votersList),
            'voters' => $votersList
        ]);
    }

    public function selfRegisteredVoters(User $leader)
    {
        $scope = $leader->leader->address->barangay->barangay_code;

        $voters = User::where('type', 'voter')->get();

        $votersList = collect();
        foreach($voters as $voter)
        {
            if($voter->address->barangay->barangay_code === $scope)
            {
                $votersList->push($this->voter($voter));
            }
        }

        return $this->respondWithSuccess([
            'number_voters' => count($votersList),
            'voters' => $votersList
        ]);
    }

    public function search($keyword)
    {
        return $this->searchUser($keyword,'voters');
    }

    public function setVoterStance(HttpRequest $request)
    {
        $stance = VoterStance::where('user_id', $request->user_id)->first();
        $user = User::find($request->user_id);
        $barangay = Barangay::where('barangay_code', $user->address->barangay_code)->first();

        if($stance) {
            if($stance->stance === $request->stance) {
                return $this->respondOk('Nothing to update');
            }
            $this->updateBarangayVoterStance($request->stance, $barangay, $stance->stance);
            $stance->stance = $request->stance;
            $stance->save();
           return $this->respondOk('Successfully Updated');
        }

        VoterStance::create([
            'user_id' => $request->user_id,
            'stance' => $request->stance
        ]);

        $this->updateBarangayVoterStance($request->stance, $barangay);

        return $this->respondOk('Successfully added');
    }

    private function updateBarangayVoterStance($stance, Barangay $barangay, $previousStance = null)
    {
        $barangayStance = BarangayVoterStance::where('barangay_code', $barangay->barangay_code)->first();

        if($barangayStance) {
            if ($previousStance === 'Pro') {
                $barangayStance->pro = $barangayStance->pro - 1;
            } elseif ($previousStance === 'Non-pro') {
               $barangayStance->non_pro = $barangayStance->non_pro - 1;
            } elseif ($previousStance === 'Undecided') {
                $barangayStance->undecided = $barangayStance->undecided - 1;
            }

            if ($stance === 'Pro') {
                $barangayStance->pro = $barangayStance->pro + 1;
            } elseif ($stance === 'Non-pro') {
                $barangayStance->non_pro = $barangayStance->non_pro + 1;
            } else {
                $barangayStance->undecided = $barangayStance->undecided + 1;
            }
            $barangayStance->save();
        } else {
            $pro = 0;
            $nonPro = 0;
            $undecided = 0;

            if ($stance === 'Pro') {
                $pro = 1;
            } elseif ($stance === 'Non-pro') {
                $nonPro = 1;
            } else {
                $undecided = 1;
            }

            BarangayVoterStance::create([
                'pro'           => $pro,
                'non_pro'       => $nonPro,
                'undecided'     => $undecided,
                'barangay_code' => $barangay->barangay_code,
                'city_code'     => $barangay->city_municipality_code,
                'province_code'  => $barangay->province_code,
                'region_code'   => $barangay->region_code
            ]);
        }
    }

}
