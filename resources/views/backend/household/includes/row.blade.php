
<x-livewire-tables::bs4.table.cell>
    {{ $row->id }}
</x-livewire-tables::bs4.table.cell>

<x-livewire-tables::bs4.table.cell>
    {{ $row->household_name }}
</x-livewire-tables::bs4.table.cell>

<x-livewire-tables::bs4.table.cell>
    <div id="accordionExample">
        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse{{ $row->id }}" aria-expanded="true" aria-controls="collapse{{ $row->id }}">
                {{ count($row->families) }} Voters
        </button>
        <div id="collapse{{ $row->id }}"  class="collapse"  aria-labelledby="headingOne" data-parent="#accordionExample">
            <ul class="list-group">
                @foreach ($row->families as $family)
                    <li class="list-group-item">
                        {{ $family->voter->first_name ?? 'no name'}}
                        {{ $family->voter->middle_name ?? 'no name'}}
                        {{ $family->voter->last_name ?? 'no name'}}
                    </li>
               @endforeach
            </ul>
          </div>
        </div>


</x-livewire-tables::bs4.table.cell>

<x-livewire-tables::bs4.table.cell>
    <x-utils.delete-button :href="route('admin.household.delete', ['household' => $row->id])" />
    {{-- <x-utils.delete-button  /> --}}
</x-livewire-tables::bs4.table.cell>
