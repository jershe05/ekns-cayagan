
<x-livewire-tables::bs4.table.cell>
    {{ $row->name }}
</x-livewire-tables::bs4.table.cell>

<x-livewire-tables::bs4.table.cell>
    {{ $row->phone }}
</x-livewire-tables::bs4.table.cell>

<x-livewire-tables::bs4.table.cell>
    {{
        $row->user->address->barangay->barangay_description . ' ' .
        $row->user->address->city->city_municipality_description . ' ' .
        $row->user->address->province->province_description . ' ' .
        $row->user->address->region->region_description
    }}

</x-livewire-tables::bs4.table.cell>
<x-livewire-tables::bs4.table.cell>

    @isset($row->address->zone)
        @if($row->address->zone > 0)
            {{ 'Purok : ' . $row->address->zone }}
        @endif
    @endisset
    {{  $row->address->barangay->barangay_description ?? '' }}
    {{  $row->address->city->city_municipality_description ?? ''}}
    {{  $row->address->province->province_description ?? ''}}
    {{  $row->address->region->region_description ?? '' }}
    {{ $row->address->island->name ?? ''}}

</x-livewire-tables::bs4.table.cell>
<x-livewire-tables::bs4.table.cell>
    @if($row->organization)
        {{ $row->organization->name }}
    @else
        N/A
    @endif
</x-livewire-tables::bs4.table.cell>
<x-livewire-tables::bs4.table.cell>

    @if($row->active === 1)
    <span class='badge badge-success'>@lang('Active')</span>
    @else
        <span class='badge badge-danger'>@lang('Inactive')</span>
    @endif
</x-livewire-tables::bs4.table.cell>
<x-livewire-tables::bs4.table.cell>
    @include('backend.leader.includes.actions', ['model' => $row])
</x-livewire-tables::bs4.table.cell>
