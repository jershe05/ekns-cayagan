<div>
    <div class="form-group row ">
        <div class="col-md-6 col-sm-12 ">
            <div style="position:relative">
                <div class="input-group mb-3">
                    <input
                        type="text"
                        class="form-control"
                        placeholder="Search Location..."
                        aria-label="Search Location"
                        aria-describedby="basic-addon2"
                        autocomplete="off"
                        wire:model.debounce.3000ms="term"
                        wire:keydown="search"
                        style="box-shadow: none;"
                    >
                    <div class="input-group-append">
                        <span class="input-group-text" id="basic-addon2">
                            <i class="fas fa-search"></i>
                        </span>
                    </div>
                </div>
            </div>
            <div style="position:absolute; z-index:2000; background-color: white; width:100%">
                @if(strlen($term) > 0)
                    @if($searchResults)
                        <ul class="list-group">
                            @foreach($searchResults as $key => $searchResult)
                                @if(is_array($searchResult))
                                    @if(array_key_exists('island_id', $searchResult))
                                        <li class=" btn list-group-item list-group-item-action" wire:click="select('{{ $searchResult['island_id'] }}', 'island')">
                                            <div class="d-flex justify-content-between">
                                                <h5 >
                                                    <i class="fas fa-map-marker pr-2"></i> {{ $searchResult['island_description'] }}
                                                </h5>
                                                <h6 class="text-success">
                                                    Island
                                                </h6>
                                            </div>

                                        </li>
                                    @elseif(array_key_exists('region_code', $searchResult))
                                    <li class=" btn list-group-item list-group-item-action" wire:click="select('{{ $searchResult['region_code'] }}', 'region')">
                                        <div class="d-flex justify-content-between">
                                            <h5 >
                                                <i class="fas fa-map-marker pr-2"></i> {{ $searchResult['region_description'] }}
                                            </h5>
                                            <h6 class="text-success">
                                                Region
                                            </h6>
                                        </div>

                                    </li>

                                    @elseif (array_key_exists('province_code', $searchResult))
                                        <li class=" btn list-group-item list-group-item-action" wire:click="select('{{ $searchResult['province_code'] }}', 'province')">
                                            <div class="d-flex justify-content-between">
                                                <h5 >
                                                    <i class="fas fa-map-marker pr-2"></i> {{ $searchResult['province_description'] }}
                                                </h5>
                                                <h6 class="text-success">
                                                    {{ $searchResult['region_description'] }}
                                                </h6>
                                            </div>

                                        </li>
                                    @elseif (array_key_exists('city_municipality_code', $searchResult))
                                        <li class="btn list-group-item list-group-item-action" wire:click="select('{{ $searchResult['city_municipality_code'] }}', 'city')">
                                            <div class="d-flex justify-content-between">
                                            <h5 >
                                                <i class="fas fa-map-marker pr-2"></i> {{ $searchResult['city_municipality_description'] }}
                                            </h5>
                                            <h6 class="text-success">
                                                {{ $searchResult['region_description'] .' '. $searchResult['province_description']}}
                                            </h6>
                                            </div>
                                        </li>
                                    @elseif (array_key_exists('barangay_code', $searchResult))

                                        <li class="btn list-group-item list-group-item-action" wire:click="select('{{ $searchResult['barangay_code'] }}', 'barangay')">
                                            <div class="d-flex justify-content-between">
                                                <h5 >
                                                    <i class="fas fa-map-marker pr-2"></i> {{ $searchResult['barangay_description'] }}
                                                </h5>
                                                <h6 class="text-success">
                                                    {{ $searchResult['region_description'] .' '. $searchResult['province_description'] .' '. $searchResult['city_municipality_description']}}
                                                </h6>
                                            </div>

                                        </li>
                                @endif

                                @endif
                            @endforeach

                        </ul>
                    @else
                        <li class="list-group-item">Found nothing...</li>
                    @endif
                @endif
            </div>
        </div>
    </div>
    @if($scope && ($event === 'markLeaderLocation' || $event === 'setLocationBase'))
        <div class="form-group ">
            <h5>Showing :  <i class="fas fa-map-marker pr-2"></i> {{ $scope }} Leaders</h5>
        </div>
    @endif
    @if($scope && $event === 'setVoterLocationBase')
    <div class="form-group ">
        <h5>Showing :  Voters in {{ $scope }} </h5>
    </div>
    @endif

    @if($scope && $event === 'setLocation')
    <div class="form-group ">
        <h5>Showing :  Voter's count in  {{ $scope }} </h5>
    </div>
    @endif
    @if($scope && $event === 'setLocationToAddTotalVoter')
    <div class="form-group ">
        <h5>Selected barangay :  {{ $scope }} </h5>
    </div>
    @endif
    @if($scope && $event === 'setHouseholdLocation')
    <div class="form-group ">
        <h5>Showing Households in :  {{ $scope }} </h5>
    </div>
    @endif


</div>
