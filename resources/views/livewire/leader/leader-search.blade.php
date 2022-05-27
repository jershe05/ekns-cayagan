<div>
    <div class="form-group row ">
        <div class="col-md-6 col-sm-12 ">
            <div style="position:relative">
                <div class="input-group mb-3">
                    <input
                        type="text"
                        class="form-control"
                        placeholder="Search Leader..."
                        aria-label="Search Leader"
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
                                    <li class=" btn list-group-item list-group-item-action" wire:click="select('{{ $searchResult['user_id'] }}', 'region')">
                                        <div class="d-flex justify-content-between">
                                            <h5 >
                                                <i class="fas fa-map-marker pr-2"></i>
                                                 {{ $searchResult['name'] }}
                                            </h5>
                                        </div>
                                    </li>
                            @endforeach
                        </ul>
                    @else
                        <li class="list-group-item">Found nothing...</li>
                    @endif
                @endif
            </div>
        </div>
    </div>

</div>
