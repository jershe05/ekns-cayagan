<div>
    <div class="container  px-4">
        <div class="row rounded-lg overflow-hidden shadow">
          <!-- Users box-->
          <div class="col-12 px-0">
            <div class="bg-white">
              <div class="messages-box">
                <div class="list-group rounded-0">
                    @if(count($histories))
                        @foreach ($histories as $history)
                        <a href="#" class="list-group-item list-group-item-action list-group-item-light rounded-0">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                    <div class="container">
                                        <div class="row">
                                        <div class="col-sm">
                                            <div class="media">

                                                <i class="far fa-calendar rounded-circle pr-2" width="50"></i>
                                                <div>
                                                <h6 class="mb-0 text-primary">{{ date("F j, Y, g:i a", strtotime($history->created_at)) }}</h6>
                                                </div>
                                            </div>
                                            {{-- <p class="font-italic text-muted mb-0 text-small">{{  $history->name }}</p> --}}
                                        </div>
                                        <div class="col-sm ">
                                            <small class="small font-weight-bold float-right"><i class="fas fa-mobile-alt rounded-circle pr-2" width="50"></i>{{  $history->name }}</small><br />
                                        </div>
                                        </div>
                                    </div>
                            </div>
                        </a>
                        @endforeach
                    @else
                            <div class="d-flex align-items-center justify-content-center mb-1">
                                <div class="container">
                                    <h5 class="text-dark p-5">No Records Found</h5>
                                </div>
                        </div>
                    @endif


                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
</div>
