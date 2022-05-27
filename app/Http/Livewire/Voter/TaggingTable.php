<?php

namespace App\Http\Livewire\Voter;

use App\Domains\Auth\Models\Role;
use App\Domains\Auth\Models\User;
use App\Http\Livewire\Traits\DefaultLocationTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filter;

/**
 * Class RolesTable.
 */
class TaggingTable extends DataTableComponent
{
    use DefaultLocationTrait;

    public $firstLoad = true;
    public array $perPageAccepted = [100, 200, 4000];
    public function getTableRowWireClick(User $user)
    {
        $this->emit('tagVoter', $user->id);
    }

    protected function getListeners()
    {
        return  [
            'setVoterLocationBase' => 'setVoterLocationBase',
        ];
    }

    public function filters(): array
    {
        return [
            'stance' => Filter::make('Stance')
                ->select([
                    ''          => 'Any',
                    'Pro'       => 'Pro',
                    'Non-pro'   => 'Non-pro',
                    'Undecided' => 'Undecided'
                ]),
            'status' => Filter::make('Status')
                ->select([
                    ''          => 'Any',
                    'tagged'    => 'Tagged',
                    'untagged'  => 'Untagged',
                ]),
        ];
    }

    public function setVoterLocationBase($data)
    {
        $this->locationBase = $data['code'];
        $this->firstLoad = false;
    }

    /**
     * @return Builder
     */
    public function query(): Builder
    {

        $query = User::query();
        $query->join('addresses', 'addresses.addressable_id', 'users.id')
            ->where('addresses.addressable_type', User::class)
            ->where('users.provider', null);
        $this->selectLocationLevel();
        foreach($this->locationBase as $key => $location)
        {
            if ($key !== 'island_id') {
                if($location) {
                    $query->where('addresses.' . $key, $location);
                }
            }
        }

        $query->when($this->getFilter('stance'), function ($query, $stance)  {
           return $query->join('voter_stances', 'users.id', 'voter_stances.user_id')
                ->where('voter_stances.stance', $stance);
        });

        $query->when($this->getFilter('status'), function ($query, $status)  {
            if ($status === 'tagged') {
                return $query->join('voter_stances', 'users.id', 'voter_stances.user_id');
            } elseif ($status === 'untagged') {
                return $query->join('voter_stances', 'users.id', '!=', 'voter_stances.user_id');
            }
         });

        return $query->select(DB::raw("CONCAT(first_name, ' ', middle_name, ' ', last_name) as name"),
                'phone',
                'users.id as id',
                'precinct_id'
            );
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
                ->searchable()
                ->sortable(),
            Column::make(__('Address')),
            Column::make(__('Precinct')),
            Column::make(__('Household')),
            Column::make(__('Stance')),
        ];
    }

    public function rowView(): string
    {
        return 'backend.Voter.includes.row-tagging';
    }
}
