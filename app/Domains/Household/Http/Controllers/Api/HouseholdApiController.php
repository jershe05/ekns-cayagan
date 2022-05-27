<?php
namespace App\Domains\Household\Http\Controllers\Api;
use App\Domains\Auth\Models\User;
use App\Domains\Family\Models\Family;
use App\Domains\Household\Http\Requests\AddHouseholdRequest;
use App\Domains\Household\Models\Household;
use App\Domains\Leader\Models\Leader;
use App\Domains\Misc\Actions\StoreModelAddressAction;
use App\Domains\Misc\Http\Resources\AddressResource;
use App\Domains\Misc\Http\Resources\BarangayResource;
use App\Domains\Misc\Models\Address;
use App\Domains\Misc\Models\Barangay;
use App\Domains\Misc\Models\City;
use App\Domains\Misc\Models\Province;
use App\Domains\Misc\Models\Region;
use App\Domains\Voter\Actions\StoreVoterAction;
use App\Domains\Voter\Http\Resources\VoterResource;
use App\Domains\Voter\Models\BarangayVoterStance;
use App\Domains\Voter\Models\VoterStance;
use App\Http\Requests\Api\Auth\RegisterVoterRequest;
use F9Web\ApiResponseHelpers;
use Illuminate\Http\Request;

class HouseholdApiController
{
    use ApiResponseHelpers;
   public function getHouseholdCount(User $user)
   {
        $leaderid = Leader::where('user_id', $user->id)->first()->id;
        return  $this->respondWithSuccess([
            'household_count' =>  $leaderid . $user->households()->count()
        ]);
   }

   public function addHousehold(AddHouseholdRequest $request)
   {
       $household = Household::create($request->validated());
        return $this->respondCreated($household);
   }

   public function list(User $user)
   {
    $leader = Leader::where('user_id', $user->id)->first();
    $leaderAddress = Address::where('addressable_id', $leader->id)
        ->where('addressable_type', Leader::class)->first();

    if(!isset($leaderAddress->zone)) {
        return $this->getBarangayHousehold($leader->address->barangay_code);
    }

    $households = array();
    foreach ($user->households as $household) {
        array_push($households, [
                'id' => $household->id,
                'household_name' => $household->household_name,
                'leader_id' => $household->leader_id,
                'voter_count' => count($household->families)
        ]);
    }
    return $this->respondWithSuccess([
        'number_household' => $user->households->count(),
        'household' => $households
        ]);
   }

   private function getBarangayHousehold($barangayCode) {
        $addresses = Address::where('addressable_type', Leader::class)
            ->where('barangay_code', $barangayCode)->get();
        $householdsList = array();
        foreach($addresses as $address) {
            $leader = Leader::find($address->addressable_id);
            if($leader && $address->zone !== null) {
                $households = Household::where('leader_id', $leader->user->id)->get();
                foreach($households as $household) {
                    array_push($householdsList, [
                            'id' => $household->id,
                            'household_name' => $household->household_name,
                            'leader_id' => $household->leader_id,
                            'voter_count' => count($household->families)
                    ]);
                }
            }
        }
        return $this->respondWithSuccess([
            'number_household' => count($householdsList),
            'household' => $householdsList
        ]);
   }

   public function barangayHouseholdList(Barangay $barangay)
   {
        return $this->getBarangayHousehold($barangay->barangay_code);
   }

   public function getAddress(Household $household)
   {
    $address = $household->voter->address;
    $barangay = Barangay::find($address->barangay_code);
    $city  = City::find($address->city_code);
    $province = Province::find($address->province_code);
    $region = Region::find($address->region_code);
    return $this->respondWithSuccess([
        'zone_no' => $address->zone_no,
        'barangay' => $barangay->barangay_description,
        'barangay_code' => $barangay->barangay_code,
        'city' => $city->city_municipality_description,
        'city_code' => $city->city_municipality_code,
        'province' => $province->province_description,
        'province_code' => $province->province_code,
        'region' => $region->region_description,
        'region_code' => $region->region_code
        ]);
   }

   public function addVoter(Request $request)
   {
       $user = User::find($request->voter);
       $existingVoter = Family::where('user_id', $request->voter)->first();
       if($existingVoter) {
           return $this->respondError('Already Existing');
       }
       
       $family = Family::create([
            'user_id' => $request->voter,
            'household_id' => $request->household
       ]);
       $user->family_id = $family->id;
       $user->save();

    return $this->respondCreated(['result' => 'success']);
   }

   public function removeVoter(User $voter)
   {
        $householdVoter = Family::where('user_id', $voter->id)->first();
        $voterStance = VoterStance::where('user_id', $voter->id)->first();
        $voteStanceCount = BarangayVoterStance::where('barangay_code', $voter->address->barangay_code)->first();

        if($voterStance->stance === "Pro") {
            $voteStanceCount->pro = $voteStanceCount->pro - 1;
            $voteStanceCount->save();
        } elseif($voterStance->stance === "Non-pro") {
            $voteStanceCount->non_pro = $voteStanceCount->non_pro - 1;
            $voteStanceCount->save();
        } else {
            $voteStanceCount->undecided = $voteStanceCount->undecided - 1;
            $voteStanceCount->save();
        }

        if ($householdVoter) {
            $householdVoter->delete();
            $voteStanceCount->delete();
            return $this->respondOk('successfully deleted');
        }

        return $this->respondError('something went wrong!');
   }
}
