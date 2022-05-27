<div>
    <div class="row">
        <span class="counter pull-right"></span>
        <table class="table table-hover table-bordered results">
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
              @foreach($barangayList as $key => $barangay)
              <tr>
                <td>{{ $barangay['barangay_description'] }}</td>
                <td>PRO :
                    <a href="/admin/voters?filters[stance]=Pro&barangay={{ $barangay['barangay_code'] }}&city={{ $barangay['city_municipality_code'] }}" class="btn
                     @if($barangay['status']['data']['pro_voters'] >= 40 )
                      text-white
                     @endif
                    " style="background-color: {{ $barangay['status']['color'][0] }}" >
                        {{ $barangay['status']['data']['pro_voters'] }} %
                    </a>
                    NON-PRO :
                    <a href="/admin/voters?filters[stance]=Non-pro&barangay={{ $barangay['barangay_code'] }}&city={{ $barangay['city_municipality_code'] }}" class="btn
                    @if($barangay['status']['data']['non_pro_voters'] >= 40 )
                      text-white
                     @endif
                    " style="background-color: {{ $barangay['status']['color'][1] }}" >
                        {{ $barangay['status']['data']['non_pro_voters'] }} %
                    </a>
                    UNDECIDED :
                    <a href="/admin/voters?filters[stance]=Undecided&barangay={{ $barangay['barangay_code'] }}&city={{ $barangay['city_municipality_code'] }}" class="btn
                    @if($barangay['status']['data']['undecided'] >= 40 )
                      text-white
                     @endif
                    " style="background-color: {{ $barangay['status']['color'][2] }}" >
                        {{ $barangay['status']['data']['undecided'] }} %
                    </a>
                    UNTAGGED:

                    <a href="/admin/voters?filters[status]=untagged&barangay={{ $barangay['barangay_code'] }}&city={{ $barangay['city_municipality_code'] }}" class="btn
                    @if($barangay['status']['data']['untagged_voters'] >= 40 )
                      text-white
                     @endif
                    " style="background-color: {{ $barangay['status']['color'][3] }}" >
                        {{ $barangay['status']['data']['untagged_voters'] }} %
                    </a>
                </td>
              </tr>
              @endforeach


          </tbody>
        </table>
    </div>
</div>
