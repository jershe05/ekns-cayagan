<div>
    @if($locationCode === "national")
        <livewire:analytics.regional-total-table />
    @elseif($locationCode === "region")
        <livewire:analytics.provincial-total-table regionCode="{{ $locationValue['region_code'] }}" />
    @elseif($locationCode === "province")
        <livewire:analytics.city-total-table provinceCode="{{ $locationValue['province_code'] }}" />
    @elseif($locationCode === "city")
        <livewire:analytics.barangay-total-table cityCode="{{ $locationValue['city_code'] }}" />
    @else
        <livewire:analytics.specific-barangay-total-table barangayCode="{{ $locationValue['barangay_code'] }}" />
    @endif
</div>
