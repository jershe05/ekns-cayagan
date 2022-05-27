@extends('backend.layouts.app')

@section('title', __('Voters List'))

@section('content')
<x-backend.card>
    <x-slot name="header">
        @lang('Voter List')
    </x-slot>

    <x-slot name="headerActions">
        <form class="d-none" id="upload-voters" action="{{ route('admin.import.voter') }}" method="POST" enctype="multipart/form-data">
           @csrf
            <div class="form-group">
              <label for="exampleFormControlFile1">Example file input</label>
              <input type="file" name="excel" class="form-control-file" id="exampleFormControlFile1" onchange="getElementById('upload-voters').submit()">
            </div>
          </form>
        <x-utils.link
            icon="c-icon cil-file"
            class="card-header-action"
            onclick="event.preventDefault();document.getElementById('exampleFormControlFile1').click();"
            :text="__('Upload Voters List')"
        />
    </x-slot>

    <x-slot name="body">
        @if(isset($failures))
        <table class="table table-bordered">
            <thead>
              <tr>
                <th scope="col">Row#</th>
                <th scope="col">Column</th>
                <th scope="col">Error</th>
              </tr>
            </thead>
            <tbody>
                @foreach ($failures as $failure)
                    <tr>
                        <th scope="row">{{ $failure->row() }}</th>
                        <td>{{ $failure->attribute() }}</td>
                        <td>
                            <ul class="list-group">
                            @foreach ($failure->errors() as $error)
                                <li class="list-group-item d-flex  align-items-center">
                                    <span class="badge badge-primary badge-pill">{{ $failure->values()[$failure->attribute()] }} </span> &nbsp; {{ $error }}
                                </li>

                            @endforeach
                        </ul>
                    </td>
                        {{-- <td>{{ $failure->values() }}</td> --}}
                    </tr>
                @endforeach


            </tbody>
          </table>
          @endif
    </x-slot>
</x-backend.card>
@endsection
