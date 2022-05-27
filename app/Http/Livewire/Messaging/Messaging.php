<?php

namespace App\Http\Livewire\Messaging;

use Livewire\Component;

class Messaging extends Component
{
    public $message;

    protected function getListeners()
    {
        return  [
            'resetMessage' => 'resetMessage',
        ];
    }

    public function resetMessage()
    {
        $this->message = '';
    }

    public function setMessage()
    {
        $this->emit('setMessage', $this->message);
    }

    public function render()
    {
        return view('livewire.messaging.messaging-form');
    }
}
