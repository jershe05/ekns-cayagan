<?php

use App\Domains\Auth\Http\Controllers\Frontend\Auth\RegisterController;
use App\Domains\Voter\Http\Controllers\Backend\VoterController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\TermsController;
use Tabuna\Breadcrumbs\Trail;

/*
 * Frontend Controllers
 * All route names are prefixed with 'frontend.'.
 */
Route::get('/', [HomeController::class, 'index'])
    ->name('index')
    ->breadcrumbs(function (Trail $trail) {
        $trail->push(__('Home'), route('frontend.index'));
    });

Route::get('terms', [TermsController::class, 'index'])
    ->name('pages.terms')
    ->breadcrumbs(function (Trail $trail) {
        $trail->parent('frontend.index')
            ->push(__('Terms & Conditions'), route('frontend.pages.terms'));
    });

Route::get('voter/register', [VoterController::class, 'showVoterRegistrationForm'])
    ->name('voter.registration');

Route::get('voter/register/store', [VoterController::class, 'store'])
    ->name('voter.store');

Route::get('voter/show/otp/{voter}', [VoterController::class, 'otp'])
    ->name('voter.otp');

Route::post('voter/otp/{voter}', [VoterController::class, 'confirmOtp'])
    ->name('voter.confirm.otp');

Route::get('voter/registered/{voter}', [VoterController::class, 'voterRegistered'])
    ->name('voter.registered');

