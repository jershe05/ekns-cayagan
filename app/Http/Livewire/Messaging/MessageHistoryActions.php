<?php

namespace App\Http\Livewire\Messaging;

use Livewire\Component;
use App\Domains\Messages\Models\MessageRecipient;

class MessageHistoryActions extends Component
{
    public $messageId;
    public $messageRecipients;
    protected function getListeners()
    {
        return  [
            'setMessageId' => 'setMessageId',
        ];
    }
    public function setMessageId($messageId)
    {
        $this->messageId = $messageId;
        $this->messageRecipients = MessageRecipient::where('message_id', $messageId)->get();
    }

    public function render()
    {
        return view('livewire.messaging.message-history-actions');
    }
}
