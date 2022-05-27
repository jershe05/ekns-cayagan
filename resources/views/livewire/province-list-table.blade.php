<div>
    <div class="row">
        <span class="counter pull-right"></span>
        <table class="table table-hover table-bordered results">
          <thead>
            <tr>
              <th class="col-md-5 col-xs-5">Provinces</th>
              <th class="col-md-4 col-xs-4">Status</th>
            </tr>
            <tr class="warning no-result">
              <td colspan="4"><i class="fa fa-warning"></i> No result</td>
            </tr>
          </thead>
          <tbody>
              @foreach($provinceList as $key => $province)
              <tr>
                <td>{{ $province['province_description'] }}</td>
                <td>BBM :
                    <button class="btn
                    @if($province['status']['result'] >= 40 )
                      text-white
                    @endif
                    " style="background-color: {{ $province['status']['color'][0] }}" >
                        {{ $province['status']['result'] }} %
                    </button>
                    Others :
                    <button class="btn
                    @if((100 - $province['status']['result']) >= 40 )
                      text-white
                    @endif
                    " style="background-color: {{ $province['status']['color'][1] }}" >
                        {{ 100 - $province['status']['result'] }} %
                    </button>
                </td>
                <td>
                    <button class="btn btn-primary" wire:click="loadCity('{{ $province['province_code'] }}')" >
                        View
                    </button>
                </td>
              </tr>
              @endforeach


          </tbody>
        </table>
    </div>
</div>
