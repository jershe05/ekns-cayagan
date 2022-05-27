<?php

namespace App\Http\Controllers\Backend;

use App\Domains\Auth\Models\User;
use App\Domains\Leader\Models\Leader;
use DB;
use Illuminate\Support\Facades\Session;

/**
 * Class DashboardController.
 */
class DashboardController
{
    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {

        if(auth()->user()->hasRole('Encoder'))
        {
            $leaders = Leader::all()->count();
            return view('backend.encoder.dashboard')
                ->with('leaders', $leaders);
        }

        Session::put('admin_address', auth()->user()->address);

        return view('backend.dashboard');
    }
}
