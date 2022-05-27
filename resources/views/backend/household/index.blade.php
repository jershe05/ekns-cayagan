@extends('backend.layouts.app')

@section('title', __('Household List'))

@section('content')
<x-backend.card>
    <x-slot name="header">
        @lang('Household List')
    </x-slot>

    <x-slot name="body">
        <livewire:household.location-household-list />
    </x-slot>
</x-backend.card>

@endsection
