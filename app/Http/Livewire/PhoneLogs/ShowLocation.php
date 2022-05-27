<?php

namespace App\Http\Livewire\PhoneLogs;

use App\Domains\Auth\Models\User;
use Livewire\Component;

class ShowLocation extends Component
{
    public $user;
    public function mount(User $user)
    {
        $this->user = $user;

    }

    public function render()
    {
        return view('livewire.phone-logs.show-location');
    }
}
