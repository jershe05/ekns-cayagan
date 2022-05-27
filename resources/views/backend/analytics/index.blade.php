@extends('backend.layouts.app')

@section('title', __('Voters List'))

@section('content')
<x-backend.card>
    <x-slot name="header">
        @lang('Total Provincial Voters')
    </x-slot>

    <x-slot name="headerActions">

        <x-utils.link
            icon="c-icon cil-plus"
            class="card-header-action"
            data-toggle="modal"
            data-target="#addNumberOfVoters"
            data-backdrop="static"
            data-keyboard="false"
            :text="__('Add Total Voters')"
        />
    </x-slot>

    <x-slot name="body">
        <div class="container py-4">
            <livewire:location-search event="setLocation" />
            <div class="row">
                <div class="col-md-6">
                    <x-backend.card>
                        <x-slot name="body">
                        <livewire:analytics.show-total-voters />
                    </x-slot>
                </x-backend.card>
                </div><!--col-md-8-->
            </div><!--row-->
        </div><!--container-->
        <livewire:analytics.add-total-voters />
    </x-slot>
</x-backend.card>

@endsection
