<?php

namespace App\Domains\Analytics\Actions;

class PercentagePerLocation
{
    private $locationQueries;
    private $luzonDataPercentage;
    private $visayasDataPercentage;
    private $mindanaoDataPercentage;
    private $nationalData;
    public $colors = [
        'lvl4' => 'rgb(145, 32, 32)', //voters  60% and above
        'lvl3' => 'rgb(250, 60, 60)', //voters 40% to 59%
        'lvl2' => 'rgb(255, 145, 145)', //voters 21% to 39%
        'lvl1' => 'rgb(255, 247, 0)', //voters 20% and below
    ];

    public function __construct(LocationQueries $locationQueries)
    {
        $this->locationQueries = $locationQueries;
        $this->nationalData = $this->locationQueries->national()->getNational();

    }

    public function getNationalPercentage()
    {

        $this->luzonDataPercentage = $this->getTotalPercentage(
            1,
            'scope_id',
            $this->nationalData['voters_list'],
            $this->nationalData['candidate_voters_list']
        );

        $this->visayasDataPercentage = $this->getTotalPercentage(
            2,
            'scope_id',
            $this->nationalData['voters_list'],
            $this->nationalData['candidate_voters_list']
        );

        $this->mindanaoDataPercentage = $this->getTotalPercentage(
            3,
            'scope_id',
            $this->nationalData['voters_list'],
            $this->nationalData['candidate_voters_list']
        );

        $result = number_format($this->luzonDataPercentage + $this->visayasDataPercentage + $this->mindanaoDataPercentage / 3, 1);

        return [
            'color' => [
                $this->getColorLevels($result),
                $this->getColorLevels(100 - $result)],
            'scope' => 0,
            'result' => (float)$result

        ];
    }

    public function getLuzonPercentage()
    {

        return [
            'color' => [
                $this->getColorLevels($this->luzonDataPercentage),
                $this->getColorLevels(100 - $this->luzonDataPercentage)
            ],
            'scope' => 1,
            'result' => (float)$this->luzonDataPercentage,
            'regular_voters' => $this->nationalData['voters_list']->where('scope_id', 1)->sum('total'),
            'registered_voters' => $this->nationalData['candidate_voters_list']->where('scope_id', 1)->sum('total')
        ];

    }

    public function getVisayasPercentage()
    {
        return [
            'color' => [
                $this->getColorLevels($this->visayasDataPercentage),
                $this->getColorLevels(100 - $this->visayasDataPercentage)
            ],
            'scope' => 2,
            'result' => (float)$this->visayasDataPercentage,
            'regular_voters' => $this->nationalData['voters_list']->where('scope_id', 2)->sum('total'),
            'registered_voters' => $this->nationalData['candidate_voters_list']->where('scope_id', 2)->sum('total')
        ];

    }

    public function getMindanaoPercentage()
    {
        return [
            'color' => [
                $this->getColorLevels($this->mindanaoDataPercentage),
                $this->getColorLevels(100 - $this->mindanaoDataPercentage)
            ],
            'scope' => 3,
            'result' => (float)$this->mindanaoDataPercentage,
            'regular_voters' => $this->nationalData['voters_list']->where('scope_id', 3)->sum('total'),
            'registered_voters' => $this->nationalData['candidate_voters_list']->where('scope_id', 3)->sum('total')
        ];
    }

    public function getRegionPercentage($islandId)
    {
        $regionalData = $this->locationQueries->regional($islandId)->getRegional();

        $regionalPercentage = $this->getTotalPercentage(
            $islandId,
            'scope_id',
            $regionalData['voters_list'],
            $regionalData['candidate_voters_list']
        );

        return [
            'color' => [
                $this->getColorLevels($regionalPercentage),
                $this->getColorLevels(100 - $regionalPercentage)
            ],
            'scope_id' => $islandId,
            'result' => (float)$regionalPercentage,
            'regular_voters' => $regionalData['voters_list']->where('scope_id', $islandId)->sum('total'),
            'registered_voters' => $regionalData['candidate_voters_list']->where('scope_id', $islandId)->sum('total')
        ];


    }

    public function getProvincePercentage($provinceCode)
    {
        $provincialData = $this->locationQueries->provincial($provinceCode)->getProvincial();

        $provincialData['pro_voters'] = $this->computePercetage($provincialData['total_voters'], $provincialData['pro_voters']);
        $provincialData['non_pro_voters'] = $this->computePercetage( $provincialData['total_voters'], $provincialData['non_pro_voters']);
        $provincialData['undecided'] = $this->computePercetage($provincialData['total_voters'], $provincialData['undecided']);
        $provincialData['untagged_voters'] = $this->computePercetage($provincialData['total_voters'], $provincialData['untagged_voters']);

        return [
            'color' => [
                $this->getColorLevels( $provincialData['pro_voters']),
                $this->getColorLevels($provincialData['non_pro_voters']),
                $this->getColorLevels($provincialData['undecided']),
                $this->getColorLevels($provincialData['untagged_voters']),
            ],
            'scope_id' => $provinceCode,
            'data' => $provincialData,
        ];

    }

    public function getCityPercentage($cityCode)
    {
        $cityData = $this->locationQueries->city($cityCode)->getCity();
        $cityData['pro_voters'] = $this->computePercetage($cityData['total_voters'], $cityData['pro_voters']);
        $cityData['non_pro_voters'] = $this->computePercetage( $cityData['total_voters'], $cityData['non_pro_voters']);
        $cityData['undecided'] = $this->computePercetage($cityData['total_voters'], $cityData['undecided']);
        $cityData['untagged_voters'] = $this->computePercetage($cityData['total_voters'], $cityData['untagged_voters']);
        
        return [
            'color' => [
                $this->getColorLevels( $cityData['pro_voters']),
                $this->getColorLevels($cityData['non_pro_voters']),
                $this->getColorLevels($cityData['undecided']),
                $this->getColorLevels($cityData['untagged_voters']),
            ],
            'scope_id' => $cityCode,
            'data' => $cityData,
        ];

    }

    public function getBarangayPercentage($barangayCode)
    {
        $barangayData = $this->locationQueries->barangay($barangayCode)->getBarangay();
        $barangayData['pro_voters'] = $this->computePercetage($barangayData['total_voters'], $barangayData['pro_voters']);
        $barangayData['non_pro_voters'] = $this->computePercetage($barangayData['total_voters'], $barangayData['non_pro_voters']);
        $barangayData['undecided'] = $this->computePercetage($barangayData['total_voters'], $barangayData['undecided']);
        $barangayData['untagged_voters'] = $this->computePercetage($barangayData['total_voters'], $barangayData['untagged_voters']);

        return [
            'color' => [
                $this->getColorLevels($barangayData['pro_voters']),
                $this->getColorLevels($barangayData['non_pro_voters']),
                $this->getColorLevels($barangayData['undecided']),
                $this->getColorLevels($barangayData['untagged_voters']),
            ],
            'test' => $barangayData,
            'scope_id' => $barangayCode,
            'data' => $barangayData,
        ];

        // $barangayPercentage = $this->getTotalPercentage(
        //     $cityCode,
        //     'city_municipality_code',
        //     $barangayData['voters_list'],
        //     $barangayData['candidate_voters_list']
        // );

        // return [
        //     'color' => [
        //         $this->getColorLevels($barangayPercentage),
        //         $this->getColorLevels(100 - $barangayPercentage)
        //     ],
        //     'scope_id' => $cityCode,
        //     'result' => (float)$barangayPercentage,
        //     'regular_voters' => $barangayData['voters_list']->where('city_municipality_code', $cityCode)->sum('total'),
        //     'registered_voters' => $barangayData['candidate_voters_list']->where('city_municipality_code', $cityCode)->sum('total')
        // ];

    }

    public function getSpecificBarangayPercentage($barangayCode)
    {
        $barangayData = $this->locationQueries->specificBarangay($barangayCode)->getSpecificBarangay();

        $barangayData['pro_voters'] = $this->computePercetage($barangayData['tagged_voters'], $barangayData['pro_voters']);
        $barangayData['non_pro_voters'] = $this->computePercetage($barangayData['tagged_voters'], $barangayData['non_pro_voters']);
        $barangayData['undecided'] = $this->computePercetage($barangayData['tagged_voters'], $barangayData['undecided']);

        return [
            'color' => [
                $this->getColorLevels($barangayData['pro_voters']),
                $this->getColorLevels($barangayData['non_pro_voters']),
                $this->getColorLevels($barangayData['undecided']),
            ],
            'scope_id' => $barangayCode,
            'data' => $barangayData,
        ];

    }

    private function computePercetage($totalVoters, $voters)
    {
        if ($totalVoters) {
            return number_format($voters / $totalVoters * 100, 1);
        }
        return number_format(0, 1);
    }

    private function getTotalPercentage($scopeId, $key, $votersList, $candidateVotersList)
    {
        $totalVoters = $votersList->where($key, $scopeId)->sum('total');
        $totalCandidateVoters = $candidateVotersList->where($key, $scopeId)->sum('total');

        if($totalVoters)
        {
            return number_format($totalCandidateVoters / $totalVoters * 100, 1);
        }

        return number_format(0, 1);

    }

    private function getColorLevels($percentage)
    {
        if($percentage >= 60)
        {
            return $this->colors['lvl4'];
        }

        if($percentage >= 40)
        {
            return $this->colors['lvl3'];
        }

        if($percentage >= 21)
        {
            return $this->colors['lvl2'];
        }

        return $this->colors['lvl1'];
    }

}
