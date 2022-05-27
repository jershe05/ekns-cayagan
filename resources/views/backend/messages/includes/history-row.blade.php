 <x-livewire-tables::bs4.table.cell>
    {{ $row->created_at }}
</x-livewire-tables::bs4.table.cell>
 <x-livewire-tables::bs4.table.cell>
    {{ $row->message }}
</x-livewire-tables::bs4.table.cell>

<x-livewire-tables::bs4.table.cell>
    {{ $row->scope }}
</x-livewire-tables::bs4.table.cell>
<x-livewire-tables::bs4.table.cell>
    <x-utils.link
                icon="fa fa-search"
                class="btn btn-info btn-sm"
                data-toggle="modal"
                data-target=".message-details"
                wire:click="showRecipients('{{ $row->id }}')"
                :text="__('View')"
            />
    {{-- <livewire:messaging.message-history-actions messageId="{{ $row->id }}"/> --}}
</x-livewire-tables::bs4.table.cell>
