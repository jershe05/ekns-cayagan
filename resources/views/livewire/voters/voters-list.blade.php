<div>
    {{-- <livewire:location-search event="setVoterLocationBase"/> --}}
    <livewire:search-by-address event="setVoterLocationBase" />
    <x-backend.card>
        <x-slot name="body">
            <div class="container py-4">
                <livewire:voter.voters-table />
            </div><!--container-->
        </x-slot>
    </x-backend.card>
</div>
