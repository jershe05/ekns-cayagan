<div>
    {{-- <livewire:location-search event="setHouseholdLocation"/> --}}
    <livewire:search-by-address event="setHouseholdLocation" />
    <x-backend.card>
        <x-slot name="body">
            <div class="container py-4">
                <livewire:household.households-table />
            </div><!--container-->
        </x-slot>
    </x-backend.card>
</div>
