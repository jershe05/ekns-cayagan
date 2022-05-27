<div>
    <div class="row">
        <span class="counter pull-right"></span>
        <table class="table table-hover table-bordered results">
          <thead>
            <tr>
              <th class="col-md-5 col-xs-5">Region</th>
              <th class="col-md-4 col-xs-4">Status</th>
              <th class="col-md-4 col-xs-4">Actions</th>
            </tr>
            <tr class="warning no-result">
              <td colspan="4"><i class="fa fa-warning"></i> No result</td>
            </tr>
          </thead>
          <tbody>
              @foreach($regionList as $key => $regions)
              <tr>
                <td>{{ $regions['region_description'] }}</td>
                <td>BBM :
                    <button class="btn
                    @if($regions['status']['result'] >= 40 )
                      text-white
                    @endif
                    " style="background-color: {{ $regions['status']['color'][0] }}" >
                        {{ $regions['status']['result'] }} %
                    </button>
                    Others :
                    <button class="btn
                    @if((100 - $regions['status']['result']) >= 40 )
                      text-white
                    @endif
                    " style="background-color: {{ $regions['status']['color'][1] }}" >
                        {{ 100 - $regions['status']['result'] }} %
                    </button>
                </td>
                <td>
                    <button class="btn btn-primary" wire:click="loadProvince('{{ $regions['region_code'] }}')" >
                        View
                    </button>
                </td>
              </tr>
              @endforeach


          </tbody>
        </table>
    </div>
</div>
