@extends('backend.layouts.app')

@section('title', __('View Leader'))

@section('content')
<h4 class="mb-4">
    Welcome {{ Auth::user()->first_name . ' '. Auth::user()->middle_name . ' ' . Auth::user()->last_name }}
</h4>
<div class="card" style="width: 18rem;">

    <div class="card-body">
      <h3 class="card-title text-primary">Total Leaders</h3>
      <h5 class="card-text">{{ $leaders }}</h5>
    </div>
  </div>

@endsection
