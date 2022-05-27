@extends('frontend.layouts.app')

@section('title', __('Voter Registration - OTP'))

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <x-frontend.card>
                    <x-slot name="header">
                        @lang('OTP')
                    </x-slot>

                    <x-slot name="body">
                        <form method="POST" action="{{ route('frontend.voter.confirm.otp', $voter) }}" class="digit-group" data-group-name="digits" data-autosubmit="true" autocomplete="off">
                           @csrf
                            <div class="d-flex justify-content-center">
                                <input type="text" id="digit-1" name="digit-1" data-next="digit-2" />
                                <input type="text" id="digit-2" name="digit-2" data-next="digit-3" data-previous="digit-1" />
                                <input type="text" id="digit-3" name="digit-3" data-next="digit-4" data-previous="digit-2" />
                                <input type="text" id="digit-4" name="digit-4" data-next="digit-5" data-previous="digit-3" />
                            </div>
                        </form>
                        </x-slot>
                </x-frontend.card>
            </div><!--col-md-8-->
        </div><!--row-->
    </div><!--container-->
@endsection
