<div>
    <div class="row">
        <span class="counter pull-right"></span>
        <table class="table table-hover table-bordered results" id="citytable">
          <thead>
            <tr>
              <th class="col-md-5 col-xs-5">City/Municipality</th>
              <th class="col-md-4 col-xs-4">Status</th>
            </tr>
            <tr class="warning no-result">
              <td colspan="4"><i class="fa fa-warning"></i> No result</td>
            </tr>
          </thead>
          <tbody>
              @foreach($cityList as $key => $city)
              <tr>
                <td>{{ $city['city_description'] }}</td>
                <td style="width: 40%">PRO :
                    <a href="/admin/voters?filters[stance]=Pro&city={{ $city['city_code'] }}" class="btn
                        @if($city['status']['data']['pro_voters'] >= 40 )
                        text-white
                        @endif
                        " style="background-color: {{ $city['status']['color'][0] }}" >
                            {{ $city['status']['data']['pro_voters'] }} %
                    </a>
                    NON-PRO :
                    <a href="/admin/voters?filters[stance]=Non-pro&city={{ $city['city_code'] }}" class="btn
                    @if($city['status']['data']['non_pro_voters'] >= 40 )
                      text-white
                     @endif
                    " style="background-color: {{ $city['status']['color'][1] }}" >
                        {{ $city['status']['data']['non_pro_voters'] }} %
                    </a>
                    UNDECIDED :
                    <a href="/admin/voters?filters[stance]=Undecided&city={{ $city['city_code'] }}" class="btn
                    @if($city['status']['data']['undecided'] >= 40 )
                      text-white
                     @endif
                    " style="background-color: {{ $city['status']['color'][2] }}" >
                        {{ $city['status']['data']['undecided'] }} %
                    </a>

                    <a href="/admin/voters?filters[status]=untagged&city={{ $city['city_code'] }}" class="btn
                    @if($city['status']['data']['untagged_voters'] >= 40 )
                      text-white
                     @endif
                    " style="background-color: {{ $city['status']['color'][3] }}" >
                        {{ $city['status']['data']['untagged_voters'] }} %
                    </a>
                </td>
                <td>
                    <button class="btn btn-primary" wire:click="loadBarangay('{{ $city['city_code'] }}')" >
                        View
                    </button>
                </td>
              </tr>
              @endforeach


          </tbody>
        </table>
    </div>
</div>
