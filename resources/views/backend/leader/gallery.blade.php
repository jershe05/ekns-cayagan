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
        <div class="container mt-5">
            <form action="{{route('admin.image.store')}}" method="post" enctype="multipart/form-data">
              <h3 class="text-center mb-5">Upload File in Laravel</h3>
                @csrf
                @if ($message = Session::get('success'))
                <div class="alert alert-success">
                    <strong>{{ $message }}</strong>
                </div>
              @endif

              @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                          <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
              @endif

                <div class="custom-file">
                    <input type="file" name="file" class="custom-file-input" id="chooseFile">
                    <label class="custom-file-label" for="chooseFile">Select file</label>
                </div>

                <button type="submit" name="submit" class="btn btn-primary btn-block mt-4">
                    Upload Files
                </button>
            </form>
        </div>
    </x-slot>
</x-backend.card>

@endsection
