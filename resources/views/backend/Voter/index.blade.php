@extends('backend.layouts.app')

@section('title', __('Voters List'))

@section('content')
<x-backend.card>
    <x-slot name="header">
        @lang('Voter List')
    </x-slot>

    <x-slot name="headerActions">
        <form id="upload-voters-form" action="{{ route('admin.import.voter') }}" method="POST" class="d-none" enctype="multipart/form-data">
           @csrf
            <div class="form-group">
              <label for="exampleFormControlFile1">Example file input</label>
              <input type="file" name="file" class="form-control-file" id="upload-voters" onchange="document.getElementById('upload-voters-form').submit()">
            </div>
        </form>

        <x-utils.link
            icon="c-icon cil-file"
            class="card-header-action"
            onclick="event.preventDefault();document.getElementById('upload-voters').click();"
            :text="__('Upload Voters List')"
        />
    </x-slot>

    <x-slot name="body">
        <livewire:voter.voters-list />
    </x-slot>
</x-backend.card>

@endsection
