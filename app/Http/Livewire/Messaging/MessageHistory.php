<?php

namespace App\Http\Livewire\Messaging;

use App\Domains\Messages\Models\Message;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;
use Rappasoft\LaravelLivewireTables\Views\Filter;

class MessageHistory extends DataTableComponent
{

    public function showRecipients($messageId)
    {
        $this->emit('setMessageId', $messageId);
    }
    
    public function columns(): array
    {
        return [
            Column::make(__('Date And Time'), 'created_at')
                ->sortable(),
            Column::make(__('Message'))
                ->searchable()
                ->sortable(),
            Column::make(__('Scope'))
                ->searchable()
                ->sortable(),
            Column::make(__('Action')),
        ];
    }

    public function query(): Builder
    {
        $query = Message::query();
        return $query->when($this->getFilter('search'), fn ($query, $term) =>
            $query->where('message', 'like', "%$term%")
        )->when($this->getFilter('date'), fn ($query, $date) =>
            $query->whereDate('created_at', $date)
        );
    }

    public function filters(): array
    {
        return [
            'date' => Filter::make('Date')
            ->date([
                'min' => now()->subYear()->format('Y-m-d'), // Optional
                'max' => now()->format('Y-m-d') // Optional
            ]),
        ];
    }


    public function rowView(): string
    {
        return 'backend.messages.includes.history-row';
    }
}
