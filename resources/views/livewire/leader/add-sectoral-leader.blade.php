<div >
<div class="form-row">
    @if($locationLevel === 'provincial')
    <div class="form-group col-5 pl-0" wire:ignore>
        <label for="city">Cities/Municipalities</label>
        <select id="city" class="form-control" name="scope[city_code]"
            wire:model="city" wire:change="showBarangays"  @if($isCityDisabled) disabled @endif>
        <option  value="" selected>Choose...</option>
        @if($cities)
            @foreach ($cities as $city)
            <option value="{{ $city->city_municipality_code }}">{{ $city->city_municipality_description }}</option>
            @endforeach
        @endif
        </select>
    </div>
    @endif
    <div class="form-group col-5 pl-0">
        <label for="barangay">Barangays</label>
        <select id="barangay" class="form-control" name="scope[barangay_code]"
            wire:model="barangay" wire:change="selectBarangay" @if($isBarangayDisabled) disabled @endif>
        <option  value="" selected>Choose...</option>
        @if($barangays)
            @foreach ($barangays as $barangay)
            <option value="{{ $barangay->barangay_code }}">{{ $barangay->barangay_description }}</option>
            @endforeach
        @endif
        </select>
    </div>
    <div class="form-group col-12 pl-0">
        <div class="form-check form-check-inline">
          <input wire:model="purokLeader" wire:change="showPurokInput" class="form-check-input" id="purok_leader" type="checkbox" name="scope['type']"  value="1" @if($isSelectPurokDisabled) disabled @endif>
          <label class="form-check-label" for="purok_leader">Purok Leader</label>
    </div>
  </div>
    @if($showSelectPurok)
    <div class="form-group col-md-4">
        <label for="zone">Purok/Zone #</label>
        <input type="number" wire:change="zoneOnChange" wire:model="zone" class="form-control" value="" id="zone" name="scope['zone_no']" placeholder="Purok/Zone #">
    </div>
    @endif
</div>

</div>
