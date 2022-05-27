<div>
    {{-- <livewire:location-search event="setVoterLocationBase"/> --}}
    <livewire:search-by-address event="setVoterLocationBase" />
    <div class="form-row">
        <div class="form-group col-5 pl-0">

        </div>

    </div>
    <div class="p-5">
        <div class="form-row">
            <div class="form-group col-5 pl-0">
                <div class="form-group col-12 pl-0">
                    <div class="form-check form-check-inline">
                        <label class="form-check-label" for="purok_leader">Purok Leader</label>
                      <input wire:model="number"  class="form-check-input" id="purok_leader" type="text" >
                      <button type="button" class="btn btn-primary" wire:click="searchLeader">Primary</button>
                </div>
              </div>
            </div>
            <div class="form-group col-3 pl-0">
                @isset($leader)
                {{ $leader->user->first_name }}
                {{ $leader->user->middle_name }}
                {{ $leader->user->last_name }}
                @endisset
            </div>
            <div class="form-group col-5 pl-0">
                <label for="barangay">Household</label>
                <select class="form-control"
                    wire:model="household">
                <option  value="" selected>Choose...</option>
                @if($households)
                    @foreach ($households as $household)
                    <option value="{{ $household['id'] }}">{{ $household['household_name'] }}</option>
                    @endforeach
                @endif
                </select>
            </div>

        </div>
        <div class="form-row">
        <div class="form-group col-5 pl-0">
            <div class="form-group col-12 pl-0">
                <div class="form-check form-check-inline">
                  <button type="button" class="btn btn-primary" wire:click="createHousehold">Create Household</button>
            </div>
          </div>
        </div>
        </div>

        </div>


    <x-backend.card>
        <x-slot name="body">
            <div class="container py-4">
                <livewire:voter.tagging-table />
            </div><!--container-->
        </x-slot>
    </x-backend.card>
</div>
