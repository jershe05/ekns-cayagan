<?php

namespace App\Http\Livewire\PhoneLogs;

use App\Domains\Auth\Models\User;
use App\Domains\PhoneLogs\Models\PhoneMessage;
use Livewire\Component;

class ShowMessages extends Component
{
    public $leader;
    public $threads;
    public $numbers;
    public $messages;
    public $numbersWithUnreadMessages;
    public $selected;
    public function mount(User $user)
    {
        $this->leader = $user;
        $this->setThread();
    }

    public function showMessages($phone)
    {
        $this->messages = $this->threads->where('phone', $phone)->all();
        PhoneMessage::where('phone', $phone)->update([
            'isRead' => 1
        ]);

        $this->setThread($phone);
    }

    private function setThread($phone = null)
    {   
        $this->selected = $phone;
        $this->threads = PhoneMessage::where('user_id', $this->leader->id)->get();
        $this->numbersWithUnreadMessages = $this->threads->where('isRead', null)->unique('phone');
        $allNumbers = $this->threads->unique('phone');
        
        foreach($this->numbersWithUnreadMessages as $numberWithUnreadMessages)
        {
            foreach($allNumbers as $key => $number)
            {
                if($number->phone === $numberWithUnreadMessages->phone)
                {
                    $allNumbers->forget($key);
                }
            }
        }

        $this->numbers = $allNumbers;
    }

    public function render()
    {
        return view('livewire.phone-logs.show-messages');
    }
}
