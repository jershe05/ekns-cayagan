<?php

namespace App\Http\Livewire\Household;

use App\Domains\Auth\Models\Role;
use App\Domains\Auth\Models\User;
use App\Domains\Family\Models\Family;
use App\Domains\Leader\Models\Leader;
use App\Domains\Misc\Models\Barangay;
use App\Domains\Misc\Models\City;
use App\Domains\Misc\Models\Province;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

/**
 * Class RolesTable.
 */
class HouseholdsTable extends DataTableComponent
{
    public $locationBase = [
        'island_id' => null,
        'region_code' => null,
        'province_code' => null,
        'city_code' => null,
        'barangay_code' => null
    ];

    public $firstLoad = true;

    protected function getListeners()
    {
        return  [
            'setHouseholdLocation' => 'setHouseholdLocation',
        ];
    }

    public function setHouseholdLocation($data)
    {

        $this->locationBase = $data['code'];
        $this->firstLoad = false;
    }

    /**
     * @return Builder
     */
    public function query(): Builder
    {
        if($this->firstLoad) {
            $this->selectLocationLevel();
        }
        $query = Leader::query();
        $query->join('addresses', 'addresses.addressable_id', 'leaders.id')
            ->join('households', 'households.leader_id', 'leaders.user_id')
            ->where('addresses.zone', '!=', null)
            ->where('addresses.addressable_type', Leader::class);

        foreach ($this->locationBase as $key => $location) {
            if ($location) {
                $query->where('addresses.' . $key, $location);
            }
        }

        return $query;
    }

    public function selectLocationLevel()
    {
        if(auth()->user()->address->province_code) {
            // $this->locationLevel = 'provincial';
            // $this->province = Session::get('admin_address')->province_code;
            // $this->isCityDisabled = false;
            $this->locationBase = [
                'island_id' => null,
                'region_code' => null,
                'province_code' => auth()->user()->address->province_code,
                'city_code' => null,
                'barangay_code' => null
            ];
        } else if(auth()->user()->address->city_code) {
            // $this->locationLevel = 'city';

            // $this->city = Session::get('admin_address')->city_code;
            $this->locationBase = [
                'island_id' => null,
                'region_code' => null,
                'province_code' => null,
                'city_code' =>  auth()->user()->address->city_code,
                'barangay_code' => null
            ];
        }

    }

    public function columns(): array
    {
        return [
            Column::make(__('Id'))
            ->format(function ($value, $column, $row) {
                return $row->id;
            })->asHtml(),

            Column::make(__('Household'))
                ->format(function ($value, $column, $row) {
                    return $row->household_name;
                })->asHtml(),
            Column::make(__('Leader'))
                ->format(function ($value, $column, $row) {
                    $leader = User::find($row->leader_id);
                    return $leader->first_name . ' ' . $leader->middle_name . ' ' . $leader->last_name;
                })->asHtml(),
            Column::make(__('Address'))
            ->format(function ($value, $column, $row) {
                $barangay = Barangay::where('barangay_code', $row->barangay_code)->first();
                $city = City::where('city_municipality_code', $row->city_code)->first();
                $province = Province::where('province_code', $row->province_code)->first();
                return 'Purok ' . $row->zone . ' ' . $barangay->barangay_description . ' ' . $city->city_municipality_description . ' '. $province->province_description;
            })->asHtml(),
            Column::make(__('Voters'))
            ->format(function ($value, $column, $row) {
                $families = Family::where('household_id', $row->id)->get();
                $votersListHtml = '<div id="collapse' . $row->id . '"  class="collapse"  aria-labelledby="headingOne" data-parent="#accordionExample">
                     <ul class="list-group">';
                foreach($families as $voter) {
                    $votersListHtml .= '<li class="list-group-item">';
                    if ($voter->voter) {
                        $votersListHtml .= $voter->voter->first_name . ' ' .
                        $voter->voter->middle_name  . ' ' .
                        $voter->voter->last_name;
                    }
                    $votersListHtml .= '</li>';
                }
                return '<div id="accordionExample">
                <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse' . $row->id . '" aria-expanded="true" aria-controls="collapse{{ $row->id }}">
                       Number of Voters (' . count($families) . ')
                </button>
                <div id="collapse' . $row->id . '"  class="collapse"  aria-labelledby="headingOne" data-parent="#accordionExample">
                    <ul class="list-group">' .
                    $votersListHtml .
                   ' </ul>
                  </div>
                </div>';
            })->asHtml(),
        ];
    }

}


