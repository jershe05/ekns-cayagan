<div>
    <div id="dashboard-legend" class="position-fixed bg-white border px-4 py-2 " style="z-index: 100; bottom: 3rem; right: 3rem;" draggable="true">
        <header class="d-flex align-items-center">
            <h3 class="m-0">Color Legend</h3>
            <i class="close fas fa-times ml-4 fa-lg" style="cursor: pointer;"></i>
        </header>
        <div>
            <div>
                <span>Voters 60% and above</span>
                <header style="height: 2rem; width: 100%;background-color: rgb(145, 32, 32);"></header>
            </div>
            <div>
                <span>Voters 40% to 59%</span>
                <header style="height: 2rem; width: 100%;background-color: rgb(250, 60, 60);"></header>
            </div>
            <div>
                <span>Voters 21% to 39%</span>
                <header style="height: 2rem; width: 100%;background-color: rgb(255, 145, 145);"></header>
            </div>
            <div>
                <span>Voters 20% and below</span>
                <header style="height: 2rem; width: 100%;background-color: rgb(255, 247, 0);"></header>
            </div>
        </div>
    </div>
    {{-- First Row --}}
    <div class="row align-items-stretch">
        <div class="col-md-6">
            <x-backend.card>
                <x-slot name="header">
                    <i class="fas fa-globe-asia mr-2"></i>@lang('Map')
                </x-slot>
                <x-slot name="body">
                    <div wire:ignore class="position-relative overflow-hidden">
                        <div id="voters-map" style="height: 423px"></div>
                    </div>
                </x-slot>
            </x-backend.card>
        </div>
        <div class="col-md-6">

            @if($showNational)
            <x-backend.card>
                <x-slot name="header">
                    <i class="fas fa-chart-pie mt-2"></i> @lang('National')
                </x-slot>
                <x-slot name="body">
                    <div class="row">
                        <input type="hidden" id="loadDiagram" wire:click="loadNational" />
                        <div class="col-md-8 mx-auto" style="max-height: 400px; max-width: 400px">
                            <div class="btn d-none @if($chartType === 'pie') d-block @endif">
                                <canvas id="nationalPie" width="400" height="400"></canvas>
                            </div>
                            <div class="btn d-none @if($chartType === 'bar') d-block @endif" wire:click="loadNational">
                                <canvas id="nationalBar" width="400" height="400"></canvas>
                            </div>
                            <div class="d-flex">
                                <button class="btn col-6" wire:click="setChartType('pie')">Pie</button>
                                <button class="btn col-6" wire:click="setChartType('bar')">Bar</button>
                            </div>
                        </div>
                        <div >
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title p2">National</h5>
                                    <h6 class="card-subtitle mb-2 text-muted p-2">Provinces : {{ $numberOfProvinces }}</h6>
                                    <h6 class="card-subtitle mb-2 text-muted p-2">Cities/Municipalities : {{ $numberOfCities }}</h6>
                                    <h6 class="card-subtitle mb-2 text-muted p-2">Barangays : {{ $numberOfBarangays }}</h6>
                                    <h6 class="card-subtitle mb-2 text-muted p-2">Regular Voters : {{ $regularVoters }}</h6>
                                    <h6 class="card-subtitle mb-2 text-muted p-2">Registered Voters : {{ $registeredVoters }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </x-slot>
            </x-backend.card>
            @elseif($showRegional)
            <x-backend.card>
                <x-slot name="header">
                    @lang($selectedIsland)
                    <i class="fas fa-chart-pie mt-2"></i> @lang('Regional - '. $selectedIsland)
                </x-slot>

                <x-slot name="body">
                    <div class="row">
                        <div class="col-6">
                            <div class="mx-auto" style="max-height: 400px; max-width: 400px">
                                <div class="btn d-none @if($chartType === 'pie') d-block @endif">
                                    <canvas id="regionalPie" height="400px" width="400px"></canvas>
                                </div>
                                <div class="btn d-none @if($chartType === 'bar') d-block @endif">
                                    <canvas id="regionalBar"></canvas>
                                </div>
                            </div>

                            <div class="d-flex mx-auto" style="max-width: 400px">
                                <button class="btn col-6" wire:click="setChartType('pie')">Pie</button>
                                <button class="btn col-6" wire:click="setChartType('bar')">Bar</button>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card" style="width: 18rem;">
                                <div class="card-body">
                                    <h5 class="card-title p2">{{ $selectedIsland }}</h5>
                                    <h6 class="card-subtitle mb-2 text-muted p-2">Provinces : {{ $numberOfProvinces }}</h6>
                                    <h6 class="card-subtitle mb-2 text-muted p-2">Cities/Municipalities : {{ $numberOfCities }}</h6>
                                    <h6 class="card-subtitle mb-2 text-muted p-2">Barangays : {{ $numberOfBarangays }}</h6>
                                    <h6 class="card-subtitle mb-2 text-muted p-2">Regular Voters : {{ $regularVoters }}</h6>
                                    <h6 class="card-subtitle mb-2 text-muted p-2">Registered Voters : {{ $registeredVoters }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </x-slot>
            </x-backend.card>
            @elseif ($showProvincial)
            <x-backend.card>
                <x-slot name="header">
                    @lang($selectedRegion)
                    <i class="fas fa-chart-pie mt-2"></i> @lang('Regional - '. $selectedRegion)
                </x-slot>

                <x-slot name="body">
                    <div class="row">
                        <div class="col-6">
                            <div class="mx-auto" style="max-height: 400px; max-width: 400px">
                                <div class="btn d-none @if($chartType === 'pie') d-block @endif">
                                    <canvas id="provincialPie" height="400px" width="400px"></canvas>
                                </div>
                                <div class="btn d-none @if($chartType === 'bar') d-block @endif">
                                    <canvas id="provincialBar" height="400px" width="400px"></canvas>
                                </div>
                            </div>

                            <div class="d-flex mx-auto" style="max-width: 400px">
                                <button class="btn col-6" wire:click="setChartType('pie')">Pie</button>
                                <button class="btn col-6" wire:click="setChartType('bar')">Bar</button>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card" style="width: 18rem;">
                                <div class="card-body">
                                    <h5 class="card-title p2">{{ $selectedRegion }}</h5>
                                    <h6 class="card-subtitle mb-2 text-muted p-2">Provinces : {{ $numberOfProvinces }}</h6>
                                    <h6 class="card-subtitle mb-2 text-muted p-2">Cities/Municipalities : {{ $numberOfCities }}</h6>
                                    <h6 class="card-subtitle mb-2 text-muted p-2">Barangays : {{ $numberOfBarangays }}</h6>
                                    <h6 class="card-subtitle mb-2 text-muted p-2">Regular Voters : {{ $regularVoters }}</h6>
                                    <h6 class="card-subtitle mb-2 text-muted p-2">Registered Voters : {{ $registeredVoters }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </x-slot>
            </x-backend.card>
            @elseif ($showCity)
            <x-backend.card>
                <x-slot name="header">
                    @lang($selectedProvince)
                    <i class="fas fa-chart-pie mt-2"></i> @lang('Provincial - '. $selectedProvince)
                    {{-- @if($locationLevel === 'provincial')
                        <input type="hidden" id="loadDiagram" wire:click="loadCity" />
                    @endif --}}
                </x-slot>

                <x-slot name="body">
                    <div class="row">
                        <div class="col-6">
                            <div class="mx-auto" style="max-height: 400px; max-width: 400px">
                                <div class="btn d-none @if($chartType === 'pie') d-block @endif">
                                    <canvas id="cityPie" height="400px" width="400px"></canvas>
                                </div>
                                <div class="btn d-none @if($chartType === 'bar') d-block @endif">
                                    <canvas id="cityBar" height="400px" width="400px"></canvas>
                                </div>
                            </div>

                            <div class="d-flex mx-auto" style="max-width: 400px">
                                <button class="btn col-6" wire:click="setChartType('pie')">Pie</button>
                                <button class="btn col-6" wire:click="setChartType('bar')">Bar</button>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card" style="width: 18rem;">
                                <div class="card-body">
                                    <h5 class="card-title p2">{{ $selectedProvince }}l</h5>
                                    <h6 class="card-subtitle mb-2 text-muted p-2">Cities/Municipalities : {{ $numberOfCities }}</h6>
                                    <h6 class="card-subtitle mb-2 text-muted p-2">Barangays : {{ $numberOfBarangays }}</h6>
                                    <h6 class="card-subtitle mb-2 text-muted p-2">Registered Voters : {{ $totalVoters }}</h6>
                                    <h6 class="card-subtitle mb-2 text-muted p-2">Tagged Voters : {{ $taggedVoters }}</h6>
                                    <h6 class="card-subtitle mb-2 text-muted p-2">Un-Tagged Voters : {{ $totalVoters - $taggedVoters }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </x-slot>
            </x-backend.card>
            @elseif ($showBarangay)
            <x-backend.card>
                <x-slot name="header">
                    @lang($selectedCity)
                    @if($locationLevel === 'city')
                        <input type="hidden" id="loadDiagram" wire:click="loadBarangay('')" />
                    @endif
                    <i class="fas fa-chart-pie mt-2"></i> @lang('City/Municipality - '. $selectedCity)
                </x-slot>

                <x-slot name="body">
                    <div class="row">
                        <div class="col-6">
                            <div class="mx-auto" style="max-height: 400px; max-width: 400px">
                                <div class="btn d-none @if($chartType === 'pie') d-block @endif">
                                    <canvas id="barangayPie" height="400px" width="400px"></canvas>
                                </div>
                                <div class="btn d-none @if($chartType === 'bar') d-block @endif">
                                    <canvas id="barangayBar" height="400px" width="400px"></canvas>
                                </div>
                            </div>

                            <div class="d-flex mx-auto" style="max-width: 400px">
                                <button class="btn col-6" wire:click="setChartType('pie')">Pie</button>
                                <button class="btn col-6" wire:click="setChartType('bar')">Bar</button>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card" style="width: 18rem;">
                                <div class="card-body">
                                    <h5 class="card-title p2">{{ $selectedCity }}</h5>
                                    <h6 class="card-subtitle mb-2 text-muted p-2">Barangays : {{ $numberOfBarangays }}</h6>
                                    <h6 class="card-subtitle mb-2 text-muted p-2">Registered Voters : {{ $totalVoters }}</h6>
                                    <h6 class="card-subtitle mb-2 text-muted p-2">Tagged Voters : {{ $taggedVoters }}</h6>
                                    <h6 class="card-subtitle mb-2 text-muted p-2">Un-Tagged Voters : {{ $totalVoters - $taggedVoters }}</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </x-slot>
            </x-backend.card>
            @endif
        </div>
    </div>

    @if($showNational)
    <x-backend.card>
        <x-slot name="header">
            <i class="fas fa-chart-pie mt-2"></i> @lang('Luzon, Visayas, Mindanao')
        </x-slot>
        <x-slot name="body">
            <div class="row justify-content-around">
                <div class="col-5 col-md-3 btn" wire:click="loadRegional(1, 'Luzon')">
                    <canvas id="luzonBar" width="400" height="400"></canvas>
                </div>
                <div class="col-5 col-md-3 btn" wire:click="loadRegional(2, 'Visayas')">
                    <canvas id="visayasBar" width="400" height="400"></canvas>
                </div>
                <div class="col-5 col-md-3 btn" wire:click="loadRegional(3, 'Mindanao')">
                    <canvas id="mindanaoBar" width="400" height="400"></canvas>
                </div>
            </div>
        </x-slot>
    </x-backend.card>
    @elseif($showRegional)
    <x-backend.card>
        <x-slot name="header">
            <i class="fas fa-chart-pie mt-2"></i> @lang('Regional - '. $selectedIsland)
        </x-slot>

        <x-slot name="body">
            <livewire:region-list-table islandId="{{ $selectedScopeId }}"/>
        </x-slot>
    </x-backend.card>
    @elseif($showProvincial)
    <x-backend.card>
        <x-slot name="header">
            <i class="fas fa-chart-pie mt-2"></i> @lang('Regional - '. $selectedRegion)
        </x-slot>

        <x-slot name="body">
            <livewire:province-list-table regionCode="{{ $selectedRegionCode }}"/>
        </x-slot>
    </x-backend.card>
    @elseif($showCity)
    <x-backend.card>
        <x-slot name="header">
            <i class="fas fa-chart-pie mt-2"></i> @lang('Provincial - '. $selectedProvince)
        </x-slot>

        <x-slot name="body">
            <input type="hidden" id="loadDiagram" wire:click="loadCity" />
            <livewire:city-list-table provinceCode="{{ $selectedProvinceCode }}"/>
        </x-slot>
    </x-backend.card>
    @elseif($showBarangay)
    <x-backend.card>
        <x-slot name="header">
            <i class="fas fa-chart-pie mt-2"></i> @lang('City/Municipality - '. $selectedCity)
        </x-slot>

        <x-slot name="body">
            <livewire:barangay-list-table cityCode="{{ $selectedCityCode }}"/>
        </x-slot>
    </x-backend.card>
    @endif
</div>
