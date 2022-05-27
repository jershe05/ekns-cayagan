<div>
    <div class="form-row">
    <div class="form-group col-2 pl-0">
        <label for="inputState">Island</label>
        <select id="inputState" class="form-control"
            wire:model="scope" wire:change="showRegions" name="scope[scope_id]">
        <option value="" selected>Choose...</option>
        @if($scopes)
            @foreach ($scopes as $scope)
            <option value="{{ $scope->id }}">{{ $scope->name }}</option>
            @endforeach
        @endif
        </select>
    </div>
    <div class="form-group col-5 pl-0">
        <label for="region">Region</label>
        <select id="region" class="form-control" name="scope[region_code]"
            wire:model="region" wire:change="showProvinces"  @if($isRegionDisabled) disabled @endif>
        <option  value="" selected>Choose...</option>
        @if($regions)
            @foreach ($regions as $region)
            <option value="{{ $region->region_code }}">{{ $region->region_description }}</option>
            @endforeach
        @endif
        </select>
    </div>
    <div class="form-group col-5 pl-0">
        <label for="province">Provinces</label>
        <select id="province" class="form-control" name="scope[province_code]"
            wire:model="province" wire:change="showCities"  @if($isProvinceDisabled) disabled @endif>
        <option  value="" selected>Choose...</option>
        @if($provinces)
            @foreach ($provinces as $province)
            <option value="{{ $province->province_code }}">{{ $province->province_description }}</option>
            @endforeach
        @endif
        </select>
    </div>
</div>
<div class="form-row">
    <div class="form-group col-5 pl-0">
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
    <div class="form-group col-md-4">
        <label for="middle_name">Purok/Zone #</label>
        <input type="number" class="form-control" id="zone_no" name="scope[zone_no]" placeholder="Purok/Zone#" required>
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
