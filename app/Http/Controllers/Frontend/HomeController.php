<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Support\Facades\Auth;

/**
 * Class HomeController.
 */
class HomeController
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        if(Auth::check()) {
            return redirect(route(homeRoute()));
        }

        return view('frontend.auth.login');
    }
}
