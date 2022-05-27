@extends('backend.layouts.app')

@section('title', __('Voters List'))

@section('content')
<x-backend.card>
    <x-slot name="header">
        @lang('Voter List')
    </x-slot>



    <x-slot name="body">
        <livewire:voter.tagging />
    </x-slot>
</x-backend.card>

@endsection
