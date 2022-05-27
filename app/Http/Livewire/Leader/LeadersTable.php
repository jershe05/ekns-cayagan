<?php

namespace App\Http\Livewire\Leader;

use App\Domains\Auth\Models\Role;
use App\Domains\Auth\Models\User;
use App\Domains\Leader\Models\Leader;
use App\Domains\Organization\Models\Organization;
use App\Http\Livewire\Traits\DefaultLocationTrait;
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
class LeadersTable extends DataTableComponent
{
    use DefaultLocationTrait;

    protected function getListeners()
    {
        return  [
            'setLocationBase' => 'setLocationBase',
        ];
    }

    public function setLocationBase($data)
    {
        $this->locationBase = $data['code'];
    }

    /**
     * @return Builder
     */
    public function query(): Builder
    {
        $query = Leader::query();
        $query->join('users', 'users.id', '=', 'leaders.user_id')
            ->join('addresses', 'addresses.addressable_id', '=', 'leaders.id')
            ->where('addresses.addressable_type', Leader::class);
        $this->selectLocationLevel();
        foreach($this->locationBase as $key => $location)
        {
            if ($location) {
                $query->where('addresses.' . $key, $location);
            }
        }

        return $query->select(
            DB::raw("CONCAT(first_name, ' ', middle_name, ' ', last_name) as name"),
                'phone',
                'leaders.organization_id',
                // 'organizations.id'
                'leaders.id as id',
                'leaders.user_id',
                'users.active'
            )
            ->when($this->getFilter('type'), function ($query, $type) {
                if($type === 'geographical') {
                    return $query->whereNull('leaders.organization_id');
                }

                if($type === 'sectoral') {
                    return $query->whereNotNull('leaders.organization_id');
                }
            })
            ->when($this->getFilter('status'), function ($query, $status) {
                return $query->where('active', $status);
            });
    }

    public function filters(): array
    {
        return [
            'type' => Filter::make('Type')
                ->select([
                    '' => 'Any',
                    'geographical' => 'Geographical',
                    'sectoral' => 'Sectoral',
                ]),
            'status' => Filter::make('Status')
                ->select([
                    '' => 'Any',
                    '1' => 'Active',
                    '2' => 'Inactive',
                ]),
            ];
    }

    public function columns(): array
    {
        return [

            Column::make(__('Name'))
                ->searchable(function($builder, $term) {
                    return $builder->where(DB::raw("CONCAT(first_name, ' ', middle_name, ' ', last_name)"), 'like', "%$term%" );
                })
                ->sortable(),
            Column::make(__('Phone'))
                // ->searchable()
                ->sortable(),
            Column::make(__('Address')),
            Column::make(__('Scope')),
            Column::make(__('Organization')),
                // ->searchable(function($builder, $term) {
                //     return $builder
                //         ->orWhere('organizations.name', 'like', "%$term%");
                // }),
            Column::make(__('Status')),
            Column::make(__('Actions')),
        ];
    }


    public function rowView(): string
    {
        return 'backend.leader.includes.row';
    }
}
