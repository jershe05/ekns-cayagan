<?php

namespace App\Domains\Analytics\Actions;
use App\Domains\Analytics\Models\TotalVotersPerLocation;
use App\Domains\Auth\Models\User;
use App\Domains\Misc\Models\Address;
use App\Domains\Misc\Models\Barangay;
use App\Domains\Misc\Models\City;
use App\Domains\Misc\Models\Province;
use App\Domains\Misc\Models\Region;
use App\Domains\Voter\Models\BarangayVoterStance;
use Cache;
use Illuminate\Support\Facades\DB;
class LocationQueries
{
    private $national;
    private $regional;
    private $provincial;
    private $city;
    private $barangay;
    private $specificBarangay;

    public function national()
    {
        $nationalVotersList = TotalVotersPerLocation::join('barangays', 'barangays.barangay_code', '=', 'total_voters_per_location.barangay_code')
            ->join('regions', 'regions.region_code', '=', 'barangays.region_code')
            ->join('island_regions', 'island_regions.region_code', '=', 'regions.region_code')
            ->select(DB::raw('SUM(total_voters_per_location.total_voters) as total'), 'island_regions.scope_id')
            ->groupBy('island_regions.scope_id')
            ->get();

        $candidateVotersList = Region::join('island_regions', 'island_regions.region_code', '=', 'regions.region_code')
            ->join('addresses', 'addresses.region_code', '=', 'regions.region_code')
            ->where('addresses.addressable_type', User::class)
            ->select(DB::raw('COUNT(addresses.region_code) as total'), 'island_regions.scope_id')
            ->groupBy('island_regions.scope_id')
            ->get();

        $this->national = [
            'voters_list'           => $nationalVotersList,
            'candidate_voters_list' => $candidateVotersList
        ];

        return $this;

    }

    public function regional($islandId)
    {
        $regionalVotersList = TotalVotersPerLocation::join('barangays', 'barangays.barangay_code', '=', 'total_voters_per_location.barangay_code')
            ->join('regions', 'regions.region_code', '=', 'barangays.region_code')
            ->join('island_regions', 'island_regions.region_code', '=', 'regions.region_code')
            ->where('island_regions.scope_id', $islandId)
            ->select(DB::raw('SUM(total_voters_per_location.total_voters) as total'), 'regions.region_code', 'island_regions.scope_id', 'regions.region_description')
            ->groupBy('regions.region_description')
            ->groupBy('island_regions.scope_id')
            ->groupBy('regions.region_code')
            ->get();


        $candidateVotersList = Region::join('island_regions', 'island_regions.region_code', '=', 'regions.region_code')
            ->join('addresses', 'addresses.region_code', '=', 'regions.region_code')
            ->where('addresses.addressable_type', User::class)
            ->where('island_regions.scope_id', $islandId)
            ->select(DB::raw('COUNT(addresses.region_code) as total'), 'island_regions.scope_id', 'regions.region_code', 'regions.region_description')
            ->groupBy('regions.region_description')
            ->groupBy('regions.region_code')
            ->groupBy('island_regions.scope_id')
            ->get();

        $this->regional = [
            'voters_list'           => $regionalVotersList,
            'candidate_voters_list' => $candidateVotersList
        ];

        return $this;
    }

    public function provincial($provinceCode)
    {

        $address = Address::query();
         $totalProvinceVotersCount = $address->join('users', 'addresses.addressable_id', 'users.id')
        ->where('addresses.province_code', $provinceCode)
        ->where('users.type', 'voter')->count();

        $taggedVoters = BarangayVoterStance::where('province_code', $provinceCode)->get();

        $taggedVotersCount =$taggedVoters->sum('pro') + $taggedVoters->sum('non_pro') + $taggedVoters->sum('undecided');

        $this->provincial = [
            'total_voters'    => $totalProvinceVotersCount,
            'tagged_voters'   => $taggedVotersCount,
            'pro_voters'      => $taggedVoters->sum('pro'),
            'non_pro_voters'  => $taggedVoters->sum('non_pro'),
            'untagged_voters' => $totalProvinceVotersCount - $taggedVotersCount,
            'undecided'       => $taggedVoters->sum('undecided'),
        ];
        return $this;
    }

    public function city($cityCode)
    {

        $totalCityVotersCount = Cache::remember($cityCode . '_set_city_cache', 86400, function () use ($cityCode) {
            $address = Address::query();
            return $address->join('users', 'addresses.addressable_id', 'users.id')
            ->where('addresses.city_code', $cityCode)
            ->where('users.type', 'voter')->count();
        });

        $taggedVoters = BarangayVoterStance::where('city_code', $cityCode)->get();
        $taggedVotersCount =$taggedVoters->sum('pro') + $taggedVoters->sum('non_pro') + $taggedVoters->sum('undecided');

        $this->city = [
            'total_voters'    => $totalCityVotersCount,
            'tagged_voters'   => $taggedVotersCount,
            'pro_voters'      => $taggedVoters->sum('pro'),
            'non_pro_voters'  => $taggedVoters->sum('non_pro'),
            'untagged_voters' => $totalCityVotersCount - $taggedVotersCount,
            'undecided'       => $taggedVoters->sum('undecided'),
        ];

        return $this;
    }

    public function barangay($barangayCode)
    {
        $totalBarangayVotersCount = Cache::remember($barangayCode . '_set_barangay_cache', 86400, function () use ($barangayCode) {
            $address = Address::query();
            return $address->join('users', 'addresses.addressable_id', 'users.id')
            ->where('addresses.barangay_code', $barangayCode)
            ->where('users.type', 'voter')->count();
        });

        $taggedVoters = BarangayVoterStance::where('barangay_code', $barangayCode)->first();
        $taggedVotersCount = 0;
        $pro = 0;
        $nonPro = 0;
        $undecided = 0;
        if ($taggedVoters) {
            $taggedVotersCount = $taggedVoters->pro + $taggedVoters->non_pro + $taggedVoters->undecided;
            $pro = $taggedVoters->pro;
            $nonPro = $taggedVoters->non_pro;
            $undecided = $taggedVoters->undecided;
        }

        $this->barangay = [
            'total_voters'   => $totalBarangayVotersCount,
            'tagged_voters'  => $taggedVotersCount,
            'pro_voters'     => $pro,
            'non_pro_voters' => $nonPro,
            'untagged_voters' => $totalBarangayVotersCount - $taggedVotersCount,
            'undecided'      => $undecided,
        ];

        return $this;
    }

    public function specificBarangay($barangayCode)
    {
        $taggedVoters = Address::join('voter_stances', 'addresses.addressable_id', '=', 'voter_stances.user_id')
            ->where('addresses.addressable_type', User::class)
            ->where('addresses.barangay_code', $barangayCode)->get();

        $totalBarangayVoters = Address::where('barangay_code', $barangayCode)
            ->where('addressable_type', User::class)->get();

        $this->specificBarangay = [
            'total_voters'   => $totalBarangayVoters->count(),
            'tagged_voters'  => $taggedVoters->count(),
            'pro_voters'     => $taggedVoters->where('stance', 'Pro')->count(),
            'non_pro_voters' => $taggedVoters->where('stance', 'Non-pro')->count(),
            'undecided'      => $taggedVoters->where('stance', 'Undecided')->count(),
        ];

    return $this;
    }

    public function getNational()
    {
        return $this->national;
    }

    public function getRegional()
    {
        return $this->regional;
    }

    public function getProvincial()
    {
        return $this->provincial;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function getBarangay()
    {
        return $this->barangay;
    }

    public function getSpecificBarangay()
    {
        return $this->specificBarangay;
    }
}
