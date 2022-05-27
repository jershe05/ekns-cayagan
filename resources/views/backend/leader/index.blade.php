@extends('backend.layouts.app')

@section('title', __('Voters List'))

@section('content')
<x-backend.card>
    <x-slot name="header">
        @lang('Leaders list')
    </x-slot>

    <x-slot name="headerActions">

        <x-utils.link
            icon="c-icon cil-plus"
            class="card-header-action"
            data-toggle="modal"
            data-target="#addLeader"
            data-backdrop="static"
            data-keyboard="false"
            :text="__('Add Leader')"
        />
    </x-slot>

    <x-slot name="body">
        <div class="container py-4">
            <div class="row">
                <div class="col-md-12">
                    <livewire:leader.leaders-list />
                </div><!--col-md-8-->
            </div><!--row-->
        </div><!--container-->
        <div>
        </div>
        <livewire:leader.add-leader />
        
    </x-slot>
</x-backend.card>

@endsection
