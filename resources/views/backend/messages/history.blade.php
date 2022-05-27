@extends('backend.layouts.app')

@section('title', __('NOTIFICATIONS'))

@section('content')
<x-backend.card>
    <x-slot name="header">
        @lang('General Message History')
    </x-slot>

    <x-slot name="body">
        <div class="container py-4">
            <div class="row">
                <div class="col-md-12">
                    <livewire:messaging.message-history />
                </div><!--col-md-8-->
            </div><!--row-->
        </div><!--container-->
        <livewire:messaging.message-history-actions />
    </x-slot>
</x-backend.card>

@endsection
