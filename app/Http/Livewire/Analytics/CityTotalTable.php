<?php

namespace App\Http\Livewire\Analytics;

use App\Domains\Analytics\Models\TotalVotersPerLocation;
use App\Domains\Auth\Models\Role;
use App\Domains\Auth\Models\User;
use App\Domains\Leader\Models\Leader;
use App\Domains\Misc\Models\City;
use App\Domains\Misc\Models\Province;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filter;

/**
 * Class RolesTable.
 */
class CityTotalTable extends DataTableComponent
{
    public function mount($provinceCode)
    {
        Session::put('province_code', $provinceCode);
    }

    /**
     * @return Builder
     */
    public function query(): Builder
    {
        $query = City::where('cities.province_code', Session::get('province_code'));
        return $query;
    }


    public function columns(): array
    {
        return [
            Column::make(__('City/Municipality'), 'city_municipality_description')
                ->searchable()
                ->sortable(),
            Column::make(__('Total Voters'), 'total')
                ->format(function ($value, $column, $row) {
                    if($row->totalVoters)
                    {
                        return $row->totalVoters->sum('total_voters');
                    }
                    return 0;
                })->asHtml(),
            Column::make(__('Total Registered Voters'), 'totalRegistered')
            ->format(function ($value, $column, $row) {
                return User::join('addresses', 'addresses.addressable_id', 'users.id')
                    ->where('addresses.addressable_type', User::class)
                    ->where('addresses.city_code', $row->city_municipality_code)->count();
            })->asHtml(),
        ];
    }

}
