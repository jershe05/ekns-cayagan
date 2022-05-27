<div>
    <div class="form-group">
        <label for="exampleFormControlTextarea1">Message</label>
        <textarea wire:model="message" wire:change="setMessage" class="form-control" id="message-content" rows="3"></textarea>
      </div>
      <x-backend.card>
        <x-slot name="header">
            @lang('Recipients')
        </x-slot>

        <x-slot name="body">
            <div class="container py-4">
                <div class="row">
                    <div class="col-md-12">
                        <livewire:search-by-address event="setRecipientLocation"/>
                        <livewire:messaging.recipient-table />
                    </div><!--col-md-8-->
                </div><!--row-->
            </div><!--container-->
        </x-slot>
    </x-backend.card>

</div>
