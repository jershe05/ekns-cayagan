<?php

namespace App\Domains\Auth\Http\Controllers\Api\Traits;

use App\Domains\Auth\Models\ApiPermission;
use App\Domains\Auth\Models\Role;
use App\Domains\Auth\Models\User;
use App\Domains\Candidate\Models\Candidate;
use App\Domains\Leader\Models\Leader;
use App\Domains\Misc\Http\Resources\AddressResource;
use App\Domains\Misc\Models\Address;
use App\Domains\Misc\Models\Barangay;
use App\Domains\Misc\Models\City;
use App\Domains\Misc\Models\Precinct;
use App\Domains\Misc\Models\Province;
use App\Domains\Misc\Models\Region;
use App\Domains\Misc\Repositories\AddressRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
trait UserMisc
{
    public function permissions(User $user)
    {
        if ($user->hasRole('Administrator'))
        {
            return ApiPermission::all()->pluck('name');
        }

        $role = User::join('model_has_roles', 'model_has_roles.model_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->where('users.id', $user->id)
            ->first([
                'roles.id'
            ]);

        return DB::table('role_has_permissions')
            ->join('roles', 'roles.id', '=', 'role_has_permissions.role_id')
            ->join('permissions', 'permissions.id', '=', 'role_has_permissions.permission_id')
            ->where('roles.id', $role->id)
            ->pluck('permissions.name');
    }

    public function address(User $user)
    {
        $address = Address::where('addressable_id', $user->id)
            ->where('addressable_type', User::class)
            ->first();
        if (!$user->hasRole('Administrator'))
        {
           return new AddressResource($address);
        }
        return [];
    }

    public function getLeaderScope(Leader $leader)
    {
        $address = Address::where('addressable_id', $leader->id)
            ->where('addressable_type', Leader::class)
            ->first();

        return new AddressResource($address);

    }

    public function getPositionName($candidate)
    {
        return DB::table('positions')->where('id', $candidate->position_id)->first(['name']);
    }

    public function getScopeName($candidate)
    {
        return DB::table('scopes')->where('id', $candidate->scope_id)->first(['name']);
    }

    public function generateReferralCode()
    {
        $isUnique = false;
        $referralCode = '';
        while ($isUnique === false) {
            $referralCode = Str::random(5);
            $match = Candidate::where('referral_code', $referralCode)->first();
            if(!$match)
            {
                $isUnique = true;
            }
        }

        return $referralCode;
    }

    public function getCandidateDetails()
    {
        if(auth()->user()->hasRole('Candidate'))
        {
            $candidate = Candidate::where('user_id', auth()->user()->id)->first();
            if(!$candidate->referral_code) {
                $referral_code = $this->generateReferralCode();
                $candidate->referral_code = $referral_code;
                $candidate->save();
                $candidate->refresh();
            }

            return [
                'position' => $this->getPositionName($candidate),
                'scope' => $this->getScopeName($candidate),
                'referral_code' => $candidate->referral_code
            ];
        }
        return null;
    }

    public function searchUser($keyword, $entity)
    {
        $query = User::query();
        $results = $query->where(function ($query) use ($keyword) {
            $query->where('first_name', 'like', '%'.$keyword.'%')
                ->orWhere('middle_name', 'like', '%'.$keyword.'%')
                ->orWhere('last_name', 'like', '%'.$keyword.'%')
                ->orWhere('email', 'like', '%'.$keyword.'%')
                ->orWhere('phone', 'like', '%'.$keyword.'%');
        })->get();

        $voters = array();
        foreach($results as $result) {
            if(!$result->family)
            {
                array_push($voters, $this->voter($result));
            }

        }

        return [
            'number_' . $entity => count($voters),
            $entity => $voters
        ];
    }

    public function leader(User $leader, Address $address)
    {
        return ['leader' => [
                'id' => $leader->id,
                'first_name' => $leader->first_name ?? 'no-name',
                'middle_name' => $leader->middle_name ?? 'no-name',
                'last_name' => $leader->last_name ?? 'no-name',
                'birthday' => $leader->birthday ?? '1988-01-01',
                'gender' => $leader->gender ?? 'male',
                'phone' => $leader->phone ?? '09000000000',
                'email' => $leader->email ?? 'test@gmail.com',
                'zone' => $address->zone
            ]];
    }

    public function voter($voter, $isVotersList = false)
    {
        if(isset($voter['precinct'])) {
            $precinct = [
                'id' => $voter['precinct']->id,
                'name' => $voter['precinct']->name
            ];
        } else {
            $precinct = [
                'id' => 1,
                'name' => '001A'
            ];
        }
        if(!isset($voter->address)) {
            $address = address::find(1);
        } else {
            $address = $voter->address;
        }

        $leaderName = 'N/A';
        $household = 'N/A';


        if($voter->family) {
            $leader =  $voter->family->household->leader;
            $leaderName = $leader->first_name . ' ' . $leader->middle_name . ' ' . $leader->last_name;
            $household = $voter->family->household->household_name;
        }

        $addressData = null;
        if (!$isVotersList) {
            $addressData = [
                'region' => AddressRepository::getRegion($address->region),
                'province' => AddressRepository::getProvince($address->province),
                'city' => AddressRepository::getCity($address->city),
                'barangay' => AddressRepository::getBarangay($address)
            ];
        }
        $voterId = $voter->id;

        if($isVotersList) {
            $voterId = $voter->addressable_id;
        }

        return [
            'voter' => [
                'id' => $voterId,
                'first_name' => $voter->first_name ?? 'no-name',
                'middle_name' => $voter->middle_name ?? 'no-name',
                'last_name' => $voter->last_name?? 'no-name',
                'birthday' => $voter->birthday ?? '1988-01-01',
                'gender' => $voter->gender ?? 'male',
                'phone' => $voter->phone ?? '09000000000',
                'precinct' => $precinct,
                'email' => $voter->email ?? 'test@gmail.com',
                'stance' => $voter->stance->stance ?? 'N/A',
                'leader' => $leaderName,
                'household' => $household,
            ],

            'address' => $addressData
        ];
    }

}
