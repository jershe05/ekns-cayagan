<?php

namespace App\Http\Livewire\PhoneLogs;

use App\Domains\Auth\Models\User;
use App\Domains\Leader\Models\Leader;
use App\Domains\PhoneLogs\Models\PhoneContact;
use Livewire\Component;

class ShowContacts extends Component
{
    public $leader;
    public $contacts;
    public function mount(User $user)
    {
        $this->leader = $user;
        $this->contacts = PhoneContact::where('user_id', $this->leader->id)->get();
    }

    public function render()
    {
        return view('livewire.phone-logs.show-contacts');
    }
}
