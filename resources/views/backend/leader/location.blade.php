@extends('backend.layouts.app')

@section('title', __('View Leader'))

@section('content')
    <x-backend.card>
        <x-slot name="header">
            @lang('Leader Location')
        </x-slot>

        {{-- <x-slot name="headerActions">
            <i class="fas fa-undo"></i>
            <x-utils.link class="card-header-action" :href="route('admin.leaders.index')" :text="__('Back')" />
        </x-slot> --}}

        <x-slot name="body">
            <livewire:location-search />
            <div id="leadersLocationMap" style="height: 800px"></div>
        </x-slot>
    </x-backend.card>

@endsection
