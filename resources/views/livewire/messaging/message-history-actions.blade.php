<div>
     <div wire:ignore.self class="modal fade message-details" tabindex="-1" role="dialog" aria-labelledby="message-details" aria-hidden="true">
        <div class="modal-dialog modal-lg">
          <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="exampleModalLabel"><i class="far fa-envelope-open pr-2"></i></i>Recipients</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <div class="p-3">
                @if($messageId)
                   <table class="table">
                      <thead>
                        <tr>
                          <th scope="col">#</th>
                          <th scope="col">Name</th>
                         
                        </tr>
                      </thead>
                      <tbody>
                      @foreach ($messageRecipients as $messageRecipient)
                          <tr>
                          <th scope="row">{{ $messageRecipient->id}}</th>
                          <td>
                          @if($messageRecipient->leader)
                            {{ 
                              $messageRecipient->leader->user->first_name .' '. 
                              $messageRecipient->leader->user->middle_name .' '.
                              $messageRecipient->leader->user->last_name
                              }}
                            @endif
                          
                          </td>
                  
                        </tr>
                      @endforeach
                       
                      
                      </tbody>
                    </table>
                @endif
              </div>
          </div>
        </div>
      </div>
    
</div>
