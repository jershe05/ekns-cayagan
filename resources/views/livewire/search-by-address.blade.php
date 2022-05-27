<div>
    <div class="form-row">
</div>
<div class="form-row">
    @if($locationLevel === 'provincial')
    <div class="form-group col-5 pl-0">
        <label for="city">Cities/Municipalities</label>
        <select id="city" class="form-control" name="scope[city_code]"
            wire:model="city_code" wire:change="showBarangays"  @if($isCityDisabled) disabled @endif>
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
            wire:model="barangay_code" wire:change="selectBarangay" @if($isBarangayDisabled) disabled @endif>
            <option  value="" selected>Choose...</option>
            @if($barangays)
                @foreach ($barangays as $barangay)
                    <option value="{{ $barangay->barangay_code }}">{{ $barangay->barangay_description }}</option>
                @endforeach
            @endif

        </select>
    </div>
</div>
<hr />
<div class="form-row">
    @if($leaders)
        <div class="form-group col-12 pl-0">
            <label for="referred_by"><h5>Select {{ $leaderLabel }}</h5></label>
            <select id="referred_by" class="form-control" name="referred_by" required>
                <option  value="" selected>Choose...</option>
                @foreach ($leaders as $leader)
                    <option value="{{ $leader->id }}" > {{ $leader->last_name . ' ' . $leader->first_name . ' ' . $leader->middle_name }}
                @endforeach
            </select>
        </div>
    @else
    <input type="hidden" class="form-control" name="referred_by" value="" />
    @endif
    <input type="hidden" class="form-control" name="organization_id" value="" />
</div>

</div>
