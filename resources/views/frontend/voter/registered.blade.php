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
                        <div class="alert alert-success" role="alert">
                            <h4 class="alert-heading">Successfully Registered!</h4>
                            <p>Thank you for your cooperation!</p>
                            <hr>
                            <p class="mb-0">You will be verified by the assigned leader in your community. Mabuhay ang Pilipinas!</p>
                          </div>
                    </x-slot>
                </x-frontend.card>
            </div><!--col-md-8-->
        </div><!--row-->
    </div><!--container-->
@endsection
