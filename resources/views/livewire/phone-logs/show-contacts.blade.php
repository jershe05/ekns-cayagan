<div>
    <div class="container  px-4">
        <div class="row rounded-lg overflow-hidden shadow">
          <!-- Users box-->
          <div class="col-12 px-0">
            <div class="bg-white">

              <div class="bg-gray px-4 py-2 bg-light">
                <p class="h5 mb-0 py-1 text-primary">List</p>
              </div>

              <div class="messages-box">
                <div class="list-group rounded-0">
                    @foreach ($contacts as $contact)
                    <a href="#" class="list-group-item list-group-item-action list-group-item-light rounded-0">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                                <div class="container">
                                    <div class="row">
                                    <div class="col-sm">
                                        <div class="media">
                                            <i class="fas fa-mobile-alt rounded-circle pr-2" width="50"></i>
                                            <div>
                                            <h6 class="mb-0">{{ $contact->full_name }}</h6>
                                            </div>
                                        </div>
                                        <p class="font-italic text-muted mb-0 text-small">{{  $contact->display_name }}</p>
                                    </div>

                                    <div class="col-sm ">
                                        @foreach ($contact->numbers as $number)
                                        <small class="small font-weight-bold float-right">{{ $number->number}}</small><br />
                                        @endforeach
                                    </div>
                                    </div>
                                </div>
                        </div>
                      </a>
                    @endforeach


                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

</div>
