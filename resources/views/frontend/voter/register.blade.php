@extends('frontend.layouts.app')

@section('title', __('Voter Registration'))

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <x-frontend.card>
                    <x-slot name="header">
                        @lang('Voter Registration')
                    </x-slot>

                    <x-slot name="body">
                        <form action="{{ route('frontend.voter.store') }}">
                            <div class="form-row">
                              <div class="form-group col-md-4">
                                <label for="first_name">First name</label>
                                <input type="text" name="first_name" class="form-control" id="first_name" placeholder="First Name" required>
                              </div>
                              <div class="form-group col-md-4">
                                <label for="middle_name">Middle Name</label>
                                <input type="text" class="form-control" id="middle_name" name="middle_name" placeholder="Middle Name" required>
                              </div>
                              <div class="form-group col-md-4">
                                <label for="last_name">Last Name</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Last Name" required>
                              </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="gender">Gender</label>
                                    <select id="gender" class="form-control" name="gender" required>
                                      <option selected>Choose...</option>
                                      <option value="male">Male</option>
                                      <option value="female">Female</option>
                                    </select>
                                  </div>
                              <div class="form-group col-md-4">
                                <label for="email">Email</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="example@gmail.com">
                              </div>
                              <div class="form-group col-md-4">
                                <label for="phone">Phone</label>
                                <input type="phone" class="form-control" id="phone" name="phone" placeholder="09XXXXXXXXX" required>
                              </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="birthday">Birthdate</label>
                                    <input type="date" class="form-control" id="birthday" name="birthday" required>
                                  </div>
                            </div>
                            <livewire:address />
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label for="precinct">Precinct</label>
                                    <select id="precinct" class="form-control" name="precinct_id">
                                      <option selected>Choose...</option>
                                      @foreach ($precincts as $precinct)
                                      <option value="{{ $precinct->id }}">{{ $precinct->name }}</option>
                                      @endforeach
                                    </select>
                                  </div>
                            </div>
                            <button type="submit" class="btn btn-primary float-right">Register</button>
                          </form>
                        </x-slot>
                </x-frontend.card>
            </div><!--col-md-8-->
        </div><!--row-->
    </div><!--container-->
@endsection
