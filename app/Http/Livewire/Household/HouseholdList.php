<?php

namespace App\Http\Livewire\Household;

use App\Domains\Auth\Models\User;
use App\Domains\Household\Models\Household;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filter;

/**
 * Class UsersTable.
 */
class HouseholdList extends DataTableComponent
{

    public function mount($leaderId)
    {
        session(['leader-id' =>  $leaderId]);
    }
    public function query(): Builder
    {
        $query = Household::where('leader_id', session('leader-id'));
        return $query;

    }

    public function columns(): array
    {
        return [
            Column::make(__('ID'))
                ->sortable(),
            Column::make(__('Household #'), 'household_name')
                ->sortable(),
            Column::make(__('# of Voters')),
            Column::make(__('Actions')),
        ];
    }

    /**
     * @return string
     */
    public function rowView(): string
    {
        return 'backend.household.includes.row';
    }
}
