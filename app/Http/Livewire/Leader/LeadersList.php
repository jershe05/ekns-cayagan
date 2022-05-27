<?php

namespace App\Http\Livewire\Leader;

use App\Domains\Scope\Models\Scope;
use Livewire\Component;

class LeadersList extends Component
{
    public $scopes;

    public function mount()
    {
        $this->scopes = Scope::all();
    }

    public function render()
    {
        return view('livewire.leader.leaders-list');
    }
}
