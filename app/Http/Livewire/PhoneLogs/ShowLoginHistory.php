<?php

namespace App\Http\Livewire\PhoneLogs;

use App\Domains\Auth\Models\User;
use Livewire\Component;

class ShowLoginHistory extends Component
{
    public $histories;

    public function mount(User $user)
    {
        $this->histories = $user->loginHistories->sortBy('created_at');
    }

    public function render()
    {
        return view('livewire.phone-logs.show-login-history');
    }
}
