<?php

namespace App\Http\Livewire\Messaging;

use App\Domains\Auth\Models\User;
use App\Domains\Leader\Models\Leader;
use App\Domains\Messages\Actions\ProcessMessageAction;
use App\Domains\Messages\Models\Message;
use App\Domains\Messages\Models\MessageRecipient;
use App\Domains\Misc\Models\Barangay;
use App\Domains\Misc\Models\City;
use App\Domains\Misc\Models\Province;
use App\Domains\Misc\Models\Region;
use App\Domains\Scope\Models\Scope;
use App\Http\Livewire\Traits\DefaultLocationTrait;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Illuminate\Support\Facades\DB;
/**
 * Class RolesTable.
 */
class RecipientTable extends DataTableComponent
{
    public $scope;
    use DefaultLocationTrait;
    protected function getListeners()
    {
        return  [
            'setMessage' => 'setMessage',
            'setRecipientLocation' => 'setRecipientLocation'
        ];
    }

    public array $bulkActions = [
        'sendMessage' => 'Send Message',
    ];

    public $message;

    public function setMessage($message)
    {
       $this->message = $message;
    }

    public function sendMessage()
    {
        if(!$this->message)
        {
            $this->emit('swal:modal', [
                'icon' => 'error',
                'title' => "Message could not be empty!",
            ]);
            return;
        }

        if (count($this->selectedKeys())) {
            $message = Message::create([
                'message' => $this->message,
                'scope' => $this->getScope()
            ]);

            if(auth()->user()->address->province_code) {
                $sender = 'AJPONCE';
            } else {
                $sender = 'AREZA';
            }

            (new ProcessMessageAction)($sender, $message, $this->selectedKeys());

            $this->emit('swal:modal', [
                'icon' => 'success',
                'title' => "Message Sent!",
                'text' => "Successfully sent message to " . count($this->selectedKeys()) . " leaders",
                'show_confirm_button' => true
            ]);

            $this->resetAll();
            $this->emit('resetMessage');
            return;
        }

        $this->emit('swal:modal', [
            'icon' => 'error',
            'title' => "Please select a recipient!",
            'text' => "failed to send message",
            'show_confirm_button' => true
        ]);
    }

    private function getScope()
    {

        foreach($this->locationBase as $location)
        {
            if($this->locationBase['barangay_code'] !== null)
            {
                return Barangay::where('barangay_code', $this->locationBase['barangay_code'])
                    ->first()->barangay_description;
            }

            if($this->locationBase['city_code'] !== null)
            {
                return City::where('city_municipality_code', $this->locationBase['city_code'])
                    ->first()->city_municipality_description;
            }

            if($this->locationBase['province_code'] !== null)
            {
                return Province::where('province_code', $this->locationBase['province_code'])
                    ->first()->province_description;
            }

            if($this->locationBase['region_code'] !== null)
            {
                return Region::where('region_code', $this->locationBase['region_code'])
                    ->first()->region_description;
            }

            if($this->locationBase['island_id'] !== null)
            {
                return Scope::where('id', $this->locationBase['island_id'])
                ->first()->name;
            }

        }
    }

    public function setRecipientLocation($locationBase)
    {
        $this->locationBase = $locationBase['code'];

    }


    /**
     * @return Builder
     */
    public function query(): Builder
    {
        $query = User::query();
        $query->join('addresses', 'addresses.addressable_id', '=', 'users.id')
            ->where('addresses.addressable_type', User::class)
            ->where('users.id', '>', 3)
            ->where('users.phone', '!=', '');

        $this->selectLocationLevel();

        foreach($this->locationBase as $key => $location)
        {
            if($location) {
                $query->where('addresses.' . $key, $location);
            }
        }

       return $query->select(DB::raw("CONCAT(first_name, ' ', middle_name, ' ', last_name) as name"),
            'phone',
            'users.id',
        )->distinct();

    }

    public function columns(): array
    {
        return [
            Column::make(__('ID')),
            Column::make(__('Name'))
            ->searchable(function($builder, $term) {
                    return $builder->where(DB::raw("CONCAT(first_name, ' ', middle_name, ' ', last_name)"), 'like', "%$term%" );
                })
                ->sortable(),
            Column::make(__('Phone'))
                ->sortable(),
                Column::make(__('Address'))
        ];
    }


    public function rowView(): string
    {
        return 'backend.messages.includes.row';
    }
}
