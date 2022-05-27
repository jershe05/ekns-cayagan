<?php

namespace App\Http\Livewire\Messaging;

use App\Domains\Messages\Models\MessageRecipient;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class MessageRecipients extends DataTableComponent
{


    public function mount($messageId)
    {
        session(['message-id' =>  $messageId]);
    }

    public function columns(): array
    {
        return [
            Column::make(__('Name'))
                ->searchable(function($builder, $term) {
                    return $builder->join('messages', 'messages.id', 'message_recipients.message_id')
                        ->join('leaders', 'leaders.id', 'message_recipients.leader_id')
                        ->join('users', 'users.id', 'leaders.user_id')
                        ->where('message_recipients.message_id', session('message-id'))
                        ->where(DB::raw("CONCAT(first_name, ' ', middle_name, ' ', last_name)"), 'like', "%$term%")
                        ->select(DB::raw("CONCAT(first_name, ' ', middle_name, ' ', last_name) as leader_name"), 'users.phone');
                })
                ->sortable(),
            Column::make(__('Phone'))
                ->sortable(),
        ];
    }

    public function query(): Builder
    {

        $query = MessageRecipient::join('messages', 'messages.id', 'message_recipients.message_id')
            ->join('leaders', 'leaders.id', 'message_recipients.leader_id')
            ->join('users', 'users.id', 'leaders.user_id')
            ->where('message_recipients.message_id', session('message-id'))
            ->select(DB::raw("CONCAT(first_name, ' ', middle_name, ' ', last_name) as leader_name"), 'users.phone');

        return $query;

    }
    public function rowView(): string
    {

        return 'backend.messages.includes.recipients-row';
    }
}
