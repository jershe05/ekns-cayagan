<div class="form-row">
    <div class="form-group col-12 pl-0">
        <label for="inputState"><h5>Address</h5></label>
    </div>
        <div class="form-group col-md-3">
            <label for="region">Region</label>
            <select id="region" class="form-control" name="address[region_code]" wire:model="region" wire:change="showProvinces" required>
            @if($selectedRegion)
                <option value="{{ $selectedRegion->region_code }}" selected>{{ $selectedRegion->region_description }}</option>
            @else
                <option selected value="">Choose...</option>
            @endif

            @foreach ($regions as $region)
                <option value="{{ $region->region_code}}">{{ $region->region_description }}</option>
            @endforeach
            </select>
        </div>
    <div class="form-group col-md-3">
        <label for="province">Province</label>
        <select id="province" class="form-control" name="address[province_code]" wire:change="showCities" wire:model="province" @if($provinceIsDisabled) disabled @endif required>
            @if($selectedProvince)
                <option value="{{ $selectedProvince->province_code }}" selected>{{ $selectedProvince->province_description }}</option>
            @else
                <option value="" selected>Choose...</option>
            @endif
          @if($provinces)
            @foreach ($provinces as $province)
                <option value="{{ $province->province_code }}">{{ $province->province_description }}</option>
            @endforeach
          @endif
        </select>
    </div>

    <div class="form-group col-md-3">
        <label for="city">City/Municipality</label>
        <select id="city" class="form-control" name="address[city_code]" wire:change="showBarangays" wire:model="city" @if($cityIsDisabled) disabled @endif required>
            @if($selectedCity)
                <option value="{{ $selectedCity->city_municipality_code }}" selected>{{ $selectedCity->city_municipality_description }}</option>
            @else
                <option selected value="">Choose...</option>
            @endif
          @if($cities)
            @foreach ($cities as $city)
                <option value="{{ $city->city_municipality_code }}">{{ $city->city_municipality_description }}</option>
            @endforeach
          @endif
        </select>
    </div>

    @if(!$isTotalVoter)
    <div class="form-group col-md-3">
        <label for="barangay">Barangay</label>
        <select id="barangay" class="form-control" name="address[barangay_code]" @if($barangayIsDisabled) disabled @endif required>
            @if($selectedBarangay)
                <option value="{{ $selectedBarangay->barangay_code }}" selected>{{ $selectedBarangay->barangay_description }}</option>
            @else
                <option selected value="">Choose...</option>
            @endif
          @if($barangays)
            @foreach ($barangays as $barangay)
                <option value="{{ $barangay->barangay_code }}">{{ $barangay->barangay_description}}</option>
            @endforeach
          @endif
        </select>
    </div>
    @endif
</div>
