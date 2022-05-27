<x-livewire-tables::bs4.table.cell>
    {{ $row->name }}
</x-livewire-tables::bs4.table.cell>

<x-livewire-tables::bs4.table.cell>
    {{ $row->phone }}
</x-livewire-tables::bs4.table.cell>

<x-livewire-tables::bs4.table.cell>
        {{
            $row->address->zone_no  . ' ' .
            $row->address->barangay?->barangay_description . ' ' .
            $row->address->city?->city_municipality_description . ' ' .
            $row->address->province?->province_description . ' ' .
            $row->address->region?->region_description
        }}

</x-livewire-tables::bs4.table.cell>

<x-livewire-tables::bs4.table.cell>
    @if($row->precinct)
        {{ $row->precinct->name }}
    @else
        000A1
    @endif

    {{-- @include('backend.Voter.includes.actions', ['model' => $row]) --}}
</x-livewire-tables::bs4.table.cell>
<x-livewire-tables::bs4.table.cell>
    @if($row->family)
        @isset($row->family->household)
            {{ $row->family->household->household_name }}
        @endisset
    @else
        N/A
    @endif


    {{-- @include('backend.Voter.includes.actions', ['model' => $row]) --}}
</x-livewire-tables::bs4.table.cell>
<x-livewire-tables::bs4.table.cell>
    @if($row->stance)
        {{ $row->stance->stance }}
    @else
        N/A
    @endif
    {{-- @include('backend.Voter.includes.actions', ['model' => $row]) --}}
</x-livewire-tables::bs4.table.cell>
