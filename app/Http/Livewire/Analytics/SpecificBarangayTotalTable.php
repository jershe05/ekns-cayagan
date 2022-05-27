<?php

namespace App\Http\Livewire\Analytics;

use App\Domains\Analytics\Models\TotalVotersPerLocation;
use App\Domains\Auth\Models\Role;
use App\Domains\Auth\Models\User;
use App\Domains\Leader\Models\Leader;
use App\Domains\Misc\Models\Barangay;
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
class SpecificBarangayTotalTable extends DataTableComponent
{

    public function mount($barangayCode)
    {
        Session::put('barangay_code', $barangayCode);
    }

    /**
     * @return Builder
     */
    public function query(): Builder
    {

        $query = Barangay::where('barangay_code', Session::get('barangay_code'));
        return $query;

    }


    public function columns(): array
    {
        return [
            Column::make(__('Barangay'), 'barangay_description')
                ->searchable()
                ->sortable(),
            Column::make(__('Total Voters'), 'total')
                ->format(function ($value, $column, $row) {
                    if($row->totalVoters)
                    {
                        return $row->totalVoters->total_voters;
                    }
                    return 0;
                })->asHtml(),
            Column::make(__('Total Registered Voters'), 'totalRegistered')
            ->format(function ($value, $column, $row) {
                return User::join('addresses', 'addresses.addressable_id', 'users.id')
                    ->where('addresses.addressable_type', User::class)
                    ->where('addresses.barangay_code', $row->barangay_code)->count();
            })->asHtml(),
        ];
    }

}
