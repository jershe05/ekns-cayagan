<?php

namespace App\Services;

use App\Domains\Analytics\Actions\LocationQueries;
use App\Domains\Analytics\Actions\PercentagePerLocation;
use App\Domains\Misc\Models\Barangay;
use App\Domains\Misc\Models\City;
use App\Domains\Misc\Models\Province;
use App\Domains\Misc\Models\Region;
use Illuminate\Support\Facades\Storage;
use Kreait\Firebase\Factory;

/**
 * Class DashboardController.
 */
class GeoJSONService extends BaseService
{
    public const luzon = [1, 2, 3, 4, 5, 6, 14, 15]; // ID based on Region Model
    public const visayas = [7, 8, 9]; // ID based on Region Model
    public const mindanao = [10, 11, 12, 13, 16, 17]; // ID based on Region Model
    private PercentagePerLocation $percentagePerLocation;

    public function __construct(PercentagePerLocation $percentagePerLocation)
    {
        $this->percentagePerLocation = $percentagePerLocation;
    }

    private function isRegionMatch($regionName, $feature): bool {
        $regionSearch = [
            'NCR' => 'Metropolitan Manila',
            'BARMM' => 'Autonomous Region of Muslim Mindanao'
        ];

        if (in_array($regionName, array_keys($regionSearch)))
            $regionName = $regionSearch[$regionName];

        return preg_match("/\b".strtolower($regionName)."\b/", strtolower($feature['properties']['REGION'])) === 1;
    }

    private function getGeoJSON($filename, $callback) {
        ini_set('memory_limit', '2048M');
        // ini_set('max_execution_time', 300);
        // $path = Storage::disk('s3')->get("json/${filename}.json");
        // dd(Storage::disk('s3')->exists("ekns/json/${filename}.json"));
        // $path = storage_path() . "/json/${filename}.json"; // ie: /var/www/laravel/app/storage/json/filename.json
        // $path = asset("json") . "/${filename}.json";
        // $path = Storage::disk('s3')->get("ekns/json/${filename}.json");
        // dd($path);
        // return json_decode(utf8_encode($path), true);
        // dd($path);

        $factory = (new Factory())
            ->withServiceAccount(base_path().'/firebase-credentials.json')
            ->withDatabaseUri('https://ekns-332410-default-rtdb.asia-southeast1.firebasedatabase.app');
        $database = $factory->createDatabase();

        $i = 0;
        // $limit = 1000;
        // $features = [];
        
        while(true) {
            $reference = $database->getReference('geojson/'.$filename.'/features/'.$i);
            
            $snapshot = $reference->getSnapshot();
            $value = $snapshot->getValue();

            if ($value === null)
                break;

            $callback($value);
            $i++;
        }

        // dd($features);

        // return $value;
        // dd($value);
        // return json_decode(utf8_encode(file_get_contents($data)), true);
    }

    private function stringRGBtoHex($rgbString) {
        preg_match("/rgb\((?P<R>[0-9]+)\,\s?(?P<G>[0-9]+)\,\s?(?P<B>[0-9]+)\s?\)/", $rgbString, $matches);

        $R = $matches['R'];
        $G = $matches['G'];
        $B = $matches['B'];

        $R = dechex($R);
        if (strlen($R)<2)
        $R = '0'.$R;

        $G = dechex($G);
        if (strlen($G)<2)
        $G = '0'.$G;

        $B = dechex($B);
        if (strlen($B)<2)
        $B = '0'.$B;

        return '#' . $R . $G . $B;
    }

    public function national() {
        $features = [];
        $geojson = $this->getGeoJSON('Region', function ($value) {
            dd($value);
        });

        // foreach ($geojson['features'] as $feature) {
        //     // $feature['properties']['total_voters'] = (rand() % (999999 - 1)) + 1;
        //     // dd($feature['geometry']);

        //     $features[] = $feature;
        // }

        $islands = ['Luzon' => GeoJSONService::luzon, 'Visayas' => GeoJSONService::visayas, 'Mindanao' => GeoJSONService::mindanao];

        foreach ($islands as $island => $ids) {
            $regions = Region::findMany($ids);

            foreach ($regions as $region) {
                if (preg_match('/\((?P<region_name>.+)\)/', $region->region_description, $matches))
                    $regionName = $matches['region_name'];

                foreach ($geojson['features'] as $key => $feature) {

                    if (preg_match("/\b".strtolower($regionName)."\b/", strtolower($feature['properties']['REGION']))) {
                        $feature['properties']['ISLAND'] = $island;

                        $result = $this->percentagePerLocation->{'get'.$island.'Percentage'}();

                        $feature['properties']['voters'] = [
                            [
                                'name' => 'BBM',
                                'percentage' => (float)$result['result'],
                                'color' => $this->stringRGBtoHex($result['color'][0])
                            ],
                            [
                                'name' => 'Others',
                                'percentage' => 100 - (float)$result['result'],
                                'color' => $this->stringRGBtoHex($result['color'][1])
                            ]
                        ];

                        $features[] = $feature;
                    }
                }
            }
        }

        return [
            'type' => 'FeatureCollection',
            'features' => $features
        ];
    }

    public function island($island) {
        if ($island === 'Luzon') $ids = GeoJSONService::luzon;
        if ($island === 'Visayas') $ids = GeoJSONService::visayas;
        if ($island === 'Mindanao') $ids = GeoJSONService::mindanao;
        $features = [];
        $geojson = $this->getGeoJSON('Region');

        $regions = Region::findMany($ids);

        foreach ($regions as $region) {
            if (preg_match('/\((?P<region_name>.+)\)/', $region->region_description, $matches))
                $regionName = $matches['region_name'];

            foreach ($geojson['features'] as $key => $feature) {

                if (preg_match("/\b".strtolower($regionName)."\b/", strtolower($feature['properties']['REGION']))) {
                    $feature['properties']['ISLAND'] = $island;

                    $result = $this->percentagePerLocation->{'get'.$island.'Percentage'}();

                    $feature['properties']['voters'] = [
                        [
                            'name' => 'BBM',
                            'percentage' => (float)$result['result'],
                            'color' => $this->stringRGBtoHex($result['color'][0])
                        ],
                        [
                            'name' => 'Others',
                            'percentage' => 100 - (float)$result['result'],
                            'color' => $this->stringRGBtoHex($result['color'][1])
                        ]
                    ];

                    $features[] = $feature;
                }
            }
        }

        return [
            'type' => 'FeatureCollection',
            'features' => $features
        ];
    }

    /**
     * Get all regions // e.g. regions();
     * Get regions by id  // e.g. regions(1)
     * Get regions by islands // e.g. regions(GeoJSONService::luzon)
     * Get regions by multiple ids // e.g. regions([1, 2])
     *
     * @return array
     */
    public function regions(int|array $ids = null)
    {
        $features = [];
        $geojson = $this->getGeoJSON('Province');

        if ($ids === null) {
            foreach ($geojson['features'] as $feature) {
                $feature['properties']['total_voters'] = (rand() % (999999 - 1)) + 1;

                $features[] = $feature;
            }
        }
        // else {
            if (gettype($ids) === 'int') $ids = [$ids];

            $regions = Region::findMany($ids);

            foreach ($regions as $region) {
                $provinces = Province::where(['region_code' => $region->region_code])->get();

                foreach ($provinces as $province) {

                    foreach ($geojson['features'] as $key => $feature) {
                        if (strtolower($feature['properties']['PROVINCE']) === strtolower($province->province_description)) {
                            // $feature['properties']['total_voters'] = (rand() % (999999 - 1)) + 1;

                            $result = $this->percentagePerLocation->getProvincePercentage($province->province_code);

                            $feature['properties']['voters'] = [
                                [
                                    'name' => 'BBM',
                                    'percentage' => (float)$result['result'],
                                    'color' => $this->stringRGBtoHex($result['color'][0])
                                ],
                                [
                                    'name' => 'Others',
                                    'percentage' => 100 - (float)$result['result'],
                                    'color' => $this->stringRGBtoHex($result['color'][1])
                                ]
                            ];

                            $features[] = $feature;
                        }
                    }
                }
            }
        // }

        return [
            'type' => 'FeatureCollection',
            'features' => $features
        ];
    }

    /**
     * Get Specific Municipality/City in a Province
     *
     * @param string $regionId
     * @param string $provinceId
     * @param string $cityId
     * @return array
     */
    public function cities(string $provinceId): array
    {
        $features = [];
        $province = Province::find($provinceId);
        $cities = City::where(['province_code' => $province->province_code])->get();
        $provinceName = $province->province_description;
        $geojson = $this->getGeoJSON('City');

        foreach ($cities as $city) {
            $cityName = $city->city_municipality_description;

            foreach ($geojson['features'] as $feature) {
                if (preg_match("/CITY OF (\b.+\b)/", $cityName))
                    $cityName = preg_replace("/CITY OF (\b.+\b)/", "$1 City", $cityName);

                $isSameMuniCity = strtolower($feature['properties']['NAME_2']) === strtolower($cityName);
                $isSameProvince = strtolower($feature['properties']['PROVINCE']) === strtolower($provinceName);

                if ($isSameMuniCity && $isSameProvince) {
                    $result = $this->percentagePerLocation->getCityPercentage($city->province_code);

                    $feature['properties']['voters'] = [
                        [
                            'name' => 'BBM',
                            'percentage' => (float)$result['result'],
                            'color' => $this->stringRGBtoHex($result['color'][0])
                        ],
                        [
                            'name' => 'Others',
                            'percentage' => 100 - (float)$result['result'],
                            'color' => $this->stringRGBtoHex($result['color'][1])
                        ]
                    ];

                    $features[] = $feature;
                }
            }
        }

        return [
            'type' => 'FeatureCollection',
            'features' => $features
        ];
    }

    /**
     * Get Specific Barangay in a Municipality/City
     *
     * @param string $regionId
     * @param string $provinceId
     * @param string $cityId
     * @param string $barangayId
     * @return array
     */
    public function barangays(string $cityId): array
    {
        $features = [];
        $city = City::find($cityId);
        $province = Province::where(['province_code' => $city->province_code])->first();
        $barangays = Barangay::where(['province_code' => $province->province_code, 'city_municipality_code' => $city->city_municipality_code])->get();

        $provinceName = $province->province_description;
        $cityName = $city->city_municipality_description;

        $geojson = $this->getGeoJSON('Barangay');

        foreach ($barangays as $barangay) {
            $barangayCode = $barangay->barangay_code;
            $barangayName = $barangay->barangay_description;


            $barangayNameRegex = array_reduce(explode(".", $barangayName), function ($oldBarangayName, $currentBarangayName) {
                $currentBarangayName = str_replace('-', '\-', $currentBarangayName);
                $currentBarangayName = str_replace('/', '\/', $currentBarangayName);

                return $oldBarangayName.trim($currentBarangayName)."\.?\s?";
            }, "");

            foreach ($geojson['features'] as $key => $feature) {
                if (preg_match("/CITY OF (\b.+\b)/", $cityName))
                    $cityName = preg_replace("/CITY OF (\b.+\b)/", "$1 City", $cityName);

                $isSameMuniCity = strtolower($feature['properties']['NAME_2']) === strtolower($cityName);
                $isSameProvince = strtolower($feature['properties']['PROVINCE']) === strtolower($provinceName);
                $isSameBarangay = preg_match("/".$barangayNameRegex."/", $feature['properties']['NAME_3']) === 1;

                if ($isSameMuniCity && $isSameProvince && $isSameBarangay) {
                    $result = $this->percentagePerLocation->getSpecificBarangayPercentage($barangayCode);

                    $feature['properties']['voters'] = [
                        [
                            'name' => 'BBM',
                            'percentage' => (float)$result['result'],
                            'color' => $this->stringRGBtoHex($result['color'][0])
                        ],
                        [
                            'name' => 'Others',
                            'percentage' => 100 - (float)$result['result'],
                            'color' => $this->stringRGBtoHex($result['color'][1])
                        ]
                    ];

                    $features[] = $feature;
                }
            }
        }

        return [
            'type' => 'FeatureCollection',
            'features' => $features
        ];
    }
}
