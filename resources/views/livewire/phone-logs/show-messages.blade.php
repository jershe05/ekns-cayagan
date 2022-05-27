<div>
    <div class="container px-4">
        <div class="row rounded-lg overflow-hidden shadow">
          <!-- Users box-->
          <div class="col-5 px-0">
            <div class="bg-white">
              <div class="bg-gray px-4 bg-light">
                <p class="h5 mb-0 py-1 text-primary">Threads</p>
              </div>

              <div class="messages-box">

                <div class="list-group rounded-0">
                @foreach ($numbersWithUnreadMessages as $numberWithUnreadMessages)
                  <a class="@if($selected === $numberWithUnreadMessages->phone) active text-white @else text-dark @endif list-group-item list-group-item-action  rounded-0 p-3 btn " wire:click="showMessages('{{ $numberWithUnreadMessages->phone }}')">
                    <div class="media"><i class="fas fa-mobile-alt"></i>
                      <div class="media-body ml-4">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <h5 class="mb-0 font-weight-bold">{{ $numberWithUnreadMessages->phone }}</h5><small class="small font-weight-bold">New</small>
                        </div>

                      </div>
                    </div>
                  </a>
                  @endforeach
                @foreach ($numbers as $number)
                  <a class="@if($selected === $number->phone) active text-white @else text-dark  @endif list-group-item  list-group-item-action rounded-0 p-3 btn "  wire:click="showMessages('{{ $number->phone }}')">
                    <div class="media"><i class="fas fa-mobile-alt"></i>
                      <div class="media-body ml-4">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <h6 class="mb-0">{{ $number->phone }}</h6><small class="small font-weight-bold"></small>
                        </div>

                      </div>
                    </div>
                  </a>
                  @endforeach
                </div>

              </div>

            </div>
          </div>

          <!-- Chat Box-->
          <div class="col-7 px-0">

            <div class="px-4 py-5 chat-box bg-white">
                @if($messages)
                    @foreach ($messages as $message)
                        @if($message->type === 'Sent')
                            <!-- Reciever Message-->
                            <div class="media w-50 ml-auto mb-3">
                                <div class="media-body">
                                <div class="bg-primary rounded py-2 px-3 mb-2">
                                    <p class="text-small mb-0 text-white">{{ $message->message }}</p>
                                </div>
                                <p class="small text-muted">{{ date('g:i a | D M j, Y', strtotime($message->date)) }}</p>
                                </div>
                            </div>
                        @else
                            <!-- Sender Message-->
                            <div class="media w-50 mb-3">
                                <div class="media-body ml-3">
                                <div class="bg-light rounded py-2 px-3 mb-2">
                                    <p class="text-small mb-0 text-muted">{{ $message->message }}</p>
                                </div>
                                <p class="small text-muted">{{ date('g:i a | D M j, Y', strtotime($message->date)) }}</p>
                                </div>
                            </div>
                        @endif
                    @endforeach
                @endif
            </div>
          </div>
        </div>
      </div>

</div>
