<?php

namespace App\Http\Livewire;

use App\Domains\Analytics\Actions\PercentagePerLocation;
use App\Domains\Misc\Models\Barangay;
use App\Domains\Misc\Models\City;
use App\Domains\Misc\Models\Province;
use App\Domains\Misc\Models\Region;
use App\Services\GeoJSONService;
use Livewire\Component;
use Session;

class Charts extends Component
{

    public $locationBase = [
        'island_id' => 1,
        'region_code' => null,
        'province_code' => null,
        'city_code' => null,
        'barangay_code' => null
    ];

    public $locationList = [
        'islands' => null,
        'regions' => null,
        'provinces' => null,
        'cities' => null,
        'barangays' => null,
    ];

    public $showNational = false;
    public $showRegional = false;
    public $showProvincial = false;
    public $showCity = false;
    public $showBarangay = false;

    public $nationalData;
    public $luzonData;
    public $visayasData;
    public $mindanaoData;
    public $dataSet = [
        'type' => null,
        'label' => null,
        'backgroudColor' => null,
        'borderColor' => null,
        'data' => null
    ];

    public $selectedIsland;
    public $selectScopeId;
    public $selectedRegion;
    public $selectedRegionCode;
    public $selectedCity;
    public $selectedCityCode;
    public $selectedProvince;
    public $selectedProvinceCode;
    public $chartType = 'pie';

    public $regularVoters;
    public $registeredVoters;
    public $numberOfCities;
    public $numberOfProvinces;
    public $numberOfBarangays;
    public $locationLevel;
    public $totalVoters;
    public $taggedVoters;
    public $unTaggedVoters;

    public function setChartType($newType) {
        $this->chartType = $newType;
    }

    protected function getListeners()
    {
        return  [
            'loadProvince' => 'loadProvince',
            'loadCity' => 'loadCity',
            'loadBarangay' => 'loadBarangay'
        ];
    }

    public function loadProvince(PercentagePerLocation $percentagePerLocation, $regionCode)
    {
        $region = Region::where('region_code', $regionCode)->first();
        $this->showNational = false;
        $this->showRegional = false;
        $this->showProvincial = true;
        $this->showCity = false;
        $this->showBarangay = false;
        $this->selectedRegion = $region->region_description;
        $this->selectedRegionCode = $regionCode;


        $provincialResult = $percentagePerLocation->getProvincePercentage($regionCode);
        $this->regularVoters = $provincialResult['regular_voters'];
        $this->registeredVoters = $provincialResult['registered_voters'];
        $this->numberOfBarangays = Barangay::where('region_code', $regionCode)->count();
        $this->numberOfCities = City::where('region_description', $regionCode)->count();
        $this->numberOfProvinces = Province::where('region_code', $regionCode)->count();

        $this->emit('pie', [
            'elementId' => 'provincialPie',
            'labels' => ['BBM', 'Others'],
            'percentage' => [(float)$provincialResult['result'], 100 - $provincialResult['result']],
            'colors' => $provincialResult['color'],
            'type' => 'pie',
            'label' => 'Provincial'
        ]);

        $candidateLabels = ['candidate' => 'BBM', 'others' => 'Others'];
        $this->emit('bar', [
            'elementId' => 'provincialBar',
            'labels' => $candidateLabels,
            'percentage' => ['candidate' => [(float)$provincialResult['result']], 'others' => [100 - $provincialResult['result']]],
            'colors' => $provincialResult['color'],
            'type' => 'bar',
            'label' => 'Provincial'
        ]);

        $provinces = [];

        foreach(Province::where('region_code', $regionCode)->get() as $province)
        {
            $provinceResult = $percentagePerLocation->getCityPercentage($province->province_code);

            $provinces[] = [
                'province_name' => $province['province_description'],
                'percentage' => [
                    'candidate' => [(float)$provinceResult['result']],
                    'others' => [100 - $provinceResult['result']]
                ],
                'colors' => $provinceResult['color'],
            ];
        }

        $this->emit('map', [
            'map_type' => 'Region',
            'args' => [$region->region_description],
            'labels' => $candidateLabels,
            'data' => $provinces
        ]);
    }

    public function loadCity(PercentagePerLocation $percentagePerLocation)
    {

        $provinceCode = $this->selectedProvinceCode;

        $province = Province::where('province_code', $provinceCode)->first();

        $this->showNational = false;
        $this->showRegional = false;
        $this->showProvincial = false;
        $this->showCity = true;
        $this->showBarangay = false;
        $this->selectedProvince = $province->province_description;
        $this->selectedProvinceCode = $provinceCode;
        $cityResult = $percentagePerLocation->getProvincePercentage($provinceCode);

        $data = $cityResult['data'];

        $this->numberOfBarangays = Barangay::where('province_code', $provinceCode)->count();
        $cityList = City::where('province_code', $provinceCode)->get();
        $this->numberOfCities = $cityList->count();
        $this->totalVoters = $data['total_voters'];
        $this->taggedVoters = $data['tagged_voters'];

        $this->emit('pie', [
            'elementId' => 'cityPie',
            'labels' => ['PRO', 'NON-PRO', 'UNDECIDED', 'UNTAGGED'],
            'percentage' =>  [$data['pro_voters'],  $data['non_pro_voters'], $data['undecided'], $data['untagged_voters']],
            'colors' => $cityResult['color'],
            'type' => 'pie',
            'label' => 'City/Municipality'
        ]);

        $candidateLabels = ['pro' => 'PRO', 'non_pro' => 'NON-PRO', 'undecided' => 'UNDECIDED', 'untagged' => 'UNTAGGED'];

        $this->emit('bar', [
            'elementId' => 'cityBar',
            'labels' => $candidateLabels,
            'percentage' => ['pro' => $data['pro_voters'], 'non_pro' => $data['non_pro_voters'], 'undecided' => $data['undecided'], 'untagged' => $data['untagged_voters']],
            'colors' => $cityResult['color'],
            'type' => 'bar',
            'label' => 'City/Municipality'
        ]);

        $cities = [];

        foreach($cityList as $city)
        {
            $cityResult = $percentagePerLocation->getCityPercentage($city->city_municipality_code);
            $cities[] = [
                'city_name' => $city->city_municipality_description,
                'percentage' => [
                    'pro' => [$data['pro_voters']],
                    'non_pro' => [$data['non_pro_voters']],
                    'undecided' => [$data['undecided']],
                    'untagged' => [$data['untagged_voters']]
                ],
                'colors' => $cityResult['color'],
            ];
        }

        $this->emit('map', [
            'map_type' => 'Province',
            'args' => [
                (Region::where('region_code', $province->region_code)->first())->region_description,
                $province->province_description,
            ],
            'labels' => $candidateLabels,
            'data' => $cities
        ]);
    }

    public function loadBarangay(PercentagePerLocation $percentagePerLocation, $cityCode)
    {
        if ($cityCode === '')
        {
            $cityCode = $this->selectedCityCode;
        }

        $city = City::where('city_municipality_code', $cityCode)->first();
        $this->showNational = false;
        $this->showRegional = false;
        $this->showProvincial = false;
        $this->showCity = false;
        $this->showBarangay = true;
        $this->selectedCity = $city->city_municipality_description;
        $this->selectedCityCode = $cityCode;
        $BarangayResult = $percentagePerLocation->getCityPercentage($cityCode);

        $this->numberOfBarangays = Barangay::where('city_municipality_code', $cityCode)->count();
        $this->numberOfCities = City::where('city_municipality_code', $cityCode)->count();

        $data = $BarangayResult['data'];

        $this->totalVoters = $data['total_voters'];
        $this->taggedVoters = $data['tagged_voters'];


        $this->emit('pie', [
            'elementId' => 'barangayPie',
            'labels' => ['PRO', 'NON-PRO', 'UNDECIDED', 'UNTAGGED'],
            'percentage' =>  [$data['pro_voters'],  $data['non_pro_voters'], $data['undecided'], $data['untagged_voters']],
            'colors' => $BarangayResult['color'],
            'type' => 'pie',
            'label' => 'Barangay'
        ]);

        $candidateLabels = ['pro' => 'PRO', 'non_pro' => 'NON-PRO', 'undecided' => 'UNDECIDED', 'untagged' => 'UNTAGGED'];

        $this->emit('bar', [
            'elementId' => 'barangayBar',
            'labels' => $candidateLabels,
            'percentage' => ['pro' => $data['pro_voters'], 'non_pro' => $data['non_pro_voters'], 'undecided' => $data['undecided'], 'untagged' => $data['untagged_voters']],
            'colors' => $BarangayResult['color'],
            'type' => 'bar',
            'label' => 'Barangay'
        ]);

        $barangays = [];

        foreach(Barangay::where('city_municipality_code', $cityCode)->get() as $barangay)
        {
            $barangayResult = $percentagePerLocation->getBarangayPercentage($barangay->barangay_code);

            $barangays[] = [
                'barangay_name' => $barangay->barangay_description,
                'percentage' => [
                    'pro' => [$data['pro_voters']],
                    'non_pro' => [$data['non_pro_voters']],
                    'undecided' => [$data['undecided']],
                    'untagged' => [$data['untagged_voters']]
                ],
                'colors' => $barangayResult['color'],
            ];
        }

        $barangay = Barangay::where('city_municipality_code', $cityCode)->first();
        $this->emit('map', [
            'map_type' => 'City',
            'args' => [
                (Region::where('region_code', $barangay->region_code)->first())->region_description,
                (Province::where('province_code', $barangay->province_code)->first())->province_description,
                (City::where('city_municipality_code', $barangay->city_municipality_code)->first())->city_municipality_description
            ],
            'labels' => $candidateLabels,
            'data' => $barangays
        ]);
    }

    public function mount(PercentagePerLocation $percentagePerLocation)
    {

        $this->nationalData = $percentagePerLocation->getNationalPercentage();
        $this->luzonData = $percentagePerLocation->getLuzonPercentage();
        $this->visayasData = $percentagePerLocation->getVisayasPercentage();
        $this->mindanaoData = $percentagePerLocation->getMindanaoPercentage();
        $this->selectLocationLevel();
    }

    public function selectLocationLevel()
    {

        if(auth()->user()->address->province_code) {
            $this->locationLevel = 'provincial';
            $this->showCity = true;
            $this->selectedProvinceCode = auth()->user()->address->province_code;

        } else if(auth()->user()->address->city_code) {
            $this->locationLevel = 'city';
            $this->showBarangay = true;
            $this->selectedCityCode = auth()->user()->address->city_code;
        }

    }

    public function loadNational()
    {
        $this->showNational = true;
        $this->showRegional = false;
        $this->showProvincial = false;
        $this->showCity = false;
        $this->showBarangay = false;
        $this->regularVoters = $this->luzonData['regular_voters'] + $this->visayasData['regular_voters'] + $this->mindanaoData['regular_voters'];
        $this->registeredVoters = $this->luzonData['registered_voters'] + $this->visayasData['regular_voters'] + $this->mindanaoData['regular_voters'];
        $this->numberOfBarangays = Barangay::all()->count();
        $this->numberOfCities = City::all()->count();
        $this->numberOfProvinces = Province::all()->count();
        $this->emit('pie', [
            'elementId' => 'nationalPie',
            'labels' => ['BBM', 'Others'],
            'percentage' => [(float)$this->nationalData['result'], 100 - $this->nationalData['result']],
            'colors' => $this->nationalData['color'],
            'type' => 'pie',
            'label' => 'National'
        ]);

        $candidateLabels = ['candidate' => 'BBM', 'others' => 'Others'];
        $this->emit('bar', [
            'elementId' => 'nationalBar',
            'labels' => $candidateLabels,
            'percentage' => ['candidate' => [(float)$this->nationalData['result']], 'others' => [100 - $this->nationalData['result']]],
            'colors' => $this->nationalData['color'],
            'type' => 'bar',
            'label' => 'National'
        ]);

        $this->emit('bar', [
            'elementId' => 'luzonBar',
            'labels' => $candidateLabels,
            'percentage' => ['candidate' => [(float)$this->luzonData['result']], 'others' => [100 - $this->luzonData['result']]],
            'colors' => $this->luzonData['color'],
            'type' => 'bar',
            'label' => 'Luzon'
        ]);

        $this->emit('bar', [
            'elementId' => 'visayasBar',
            'labels' => $candidateLabels,
            'percentage' => ['candidate' => [(float)$this->visayasData['result']], 'others' => [100 - $this->visayasData['result']]],
            'colors' => $this->visayasData['color'],
            'type' => 'bar',
            'label' => 'Visayas'
        ]);

        $this->emit('bar', [
            'elementId' => 'mindanaoBar',
            'labels' => $candidateLabels,
            'percentage' => ['candidate' => [(float)$this->mindanaoData['result']], 'others' => [100 - $this->mindanaoData['result']]],
            'colors' => $this->mindanaoData['color'],
            'type' => 'bar',
            'label' => 'Mindanao'
        ]);

        $this->emit('map', [
            'map_type' => 'National',
            'args' => [],
            'labels' => $candidateLabels,
            'data' => [
                [
                    'name' => 'Luzon',
                    'percentage' => ['candidate' => [(float)$this->luzonData['result']], 'others' => [100 - $this->luzonData['result']]],
                    'colors' => $this->luzonData['color'],
                ],
                [
                    'name' => 'Visayas',
                    'percentage' => ['candidate' => [(float)$this->visayasData['result']], 'others' => [100 - $this->visayasData['result']]],
                    'colors' => $this->visayasData['color'],
                ],
                [
                    'name' => 'Mindanao',
                    'percentage' => ['candidate' => [(float)$this->mindanaoData['result']], 'others' => [100 - $this->mindanaoData['result']]],
                    'colors' => $this->mindanaoData['color'],
                ]
            ]
        ]);

        // Barangay 41933
        //
    }

    public function loadRegional(PercentagePerLocation $percentagePerLocation, $scopeId, $island)
    {
        $this->showNational = false;
        $this->showRegional = true;
        $this->showProvincial = false;
        $this->showCity = false;
        $this->showBarangay = false;

        $this->selectedIsland = $island;
        $this->selectedScopeId = $scopeId;
        if($scopeId === 1)
        {
            $this->regularVoters = $this->luzonData['regular_voters'];
            $this->registeredVoters = $this->luzonData['registered_voters'];
        } elseif($scopeId === 2) {
            $this->regularVoters = $this->visayasData['regular_voters'];
            $this->registeredVoters = $this->visayasData['registered_voters'];
        } else {
            $this->regularVoters = $this->visayasData['regular_voters'];
            $this->registeredVoters = $this->visayasData['registered_voters'];
        }

        $this->numberOfBarangays = Barangay::join('regions', 'regions.region_code', 'barangays.region_code')
            ->join('island_regions', 'island_regions.region_code', 'regions.region_code')
            ->where('island_regions.scope_id', $scopeId)
            ->count();

        $this->numberOfCities = City::join('regions', 'regions.region_code', 'cities.region_description')
            ->join('island_regions', 'island_regions.region_code', 'regions.region_code')
            ->where('island_regions.scope_id', $scopeId)
            ->count();

        $this->numberOfProvinces = Province::join('regions', 'regions.region_code', 'provinces.region_code')
            ->join('island_regions', 'island_regions.region_code', 'regions.region_code')
            ->where('island_regions.scope_id', $scopeId)
            ->count();

        $regionalResult = $percentagePerLocation->getRegionPercentage($scopeId);
        $this->emit('pie', [
            'elementId' => 'regionalPie',
            'labels' => ['BBM', 'Others'],
            'percentage' => [
                (float)$regionalResult['result'],
                100 - $regionalResult['result']
            ],
            'colors' => $regionalResult['color'],
            'type' => 'pie',
            'label' => 'Regional'
        ]);

        $candidateLabels = ['candidate' => 'BBM', 'others' => 'Others'];
        $this->emit('bar', [
            'elementId' => 'regionalBar',
            'labels' => $candidateLabels,
            'percentage' => [
                'candidate' => [(float)$regionalResult['result']],
                'others' => [100 - $regionalResult['result']]
            ],
            'colors' => $regionalResult['color'],
            'type' => 'bar',
            'label' => 'Regional - ' . $island
        ]);

        $regions = Region::join('island_regions', 'island_regions.region_code', 'regions.region_code')
            ->where('island_regions.scope_id', $this->selectedScopeId)
            ->get();

        $regionList = [];

        foreach($regions as $region)
        {
            $regionResult = $percentagePerLocation->getProvincePercentage($region['region_code']);
            $regionList[] = [
                'region_name' => $region['region_description'],
                'percentage' => [
                    'candidate' => [(float)$regionResult['result']],
                    'others' => [100 - $regionResult['result']]
                ],
                'colors' => $regionResult['color'],
            ];
        }

        $islandName = ['Luzon', 'Visayas', 'Mindanao'];

        $this->emit('map', [
            'map_type' => 'Island',
            'args' => [$islandName[$scopeId - 1]],
            'labels' => $candidateLabels,
            'data' => $regionList
        ]);
    }

    public function render()
    {
        return view('livewire.charts');
    }
}
