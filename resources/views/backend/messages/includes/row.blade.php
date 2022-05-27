 <x-livewire-tables::bs4.table.cell>
    {{ $row->id }}
</x-livewire-tables::bs4.table.cell>
<x-livewire-tables::bs4.table.cell>
    {{ $row->name }}
</x-livewire-tables::bs4.table.cell>

<x-livewire-tables::bs4.table.cell>
    {{ $row->phone }}
</x-livewire-tables::bs4.table.cell>

<x-livewire-tables::bs4.table.cell>
    @if($row->address->zone > 0)
        {{ 'Purok : ' .  $row->address->zone}}
    @endif

 @isset($row->address->barangay)
    {{
        $row->address->barangay->barangay_description . ' ' .
        $row->address->city->city_municipality_description . ' ' .
        $row->address->province->province_description . ' ' .
        $row->address->region->region_description
    }}
    @endisset


</x-livewire-tables::bs4.table.cell>
