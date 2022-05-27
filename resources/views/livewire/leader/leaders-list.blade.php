<div>
      {{-- <livewire:location-search event="setLocationBase" /> --}}
   <livewire:search-by-address event="setLocationBase" />
        <x-backend.card>
            <x-slot name="body">
                <div class="container py-4">
                    <livewire:leader.leaders-table />
                </div><!--container-->
            </x-slot>
        </x-backend.card>
</div>
