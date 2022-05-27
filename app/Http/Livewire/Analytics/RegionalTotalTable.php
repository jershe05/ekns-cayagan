<?php

namespace App\Http\Livewire\Analytics;

use App\Domains\Analytics\Models\TotalVotersPerLocation;
use App\Domains\Auth\Models\Role;
use App\Domains\Auth\Models\User;
use App\Domains\Leader\Models\Leader;
use App\Domains\Misc\Models\Region;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Laravel\VaporCli\Regions;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filter;

/**
 * Class RolesTable.
 */
class RegionalTotalTable extends DataTableComponent
{

    public $totalNational;

    /**
     * @return Builder
     */
    public function query(): Builder
    {

        $query = Region::query();

        return $query;

    }

    public function columns(): array
    {
        return [
            Column::make(__('Region'), 'region_description')
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
            Column::make(__('Total Registered Voters'))
            ->format(function ($value, $column, $row) {
                return User::join('addresses', 'addresses.addressable_id', 'users.id')
                    ->where('addresses.addressable_type', User::class)
                    ->where('addresses.region_code', $row->region_code)->count();
            })->asHtml(),
        ];
    }


}
