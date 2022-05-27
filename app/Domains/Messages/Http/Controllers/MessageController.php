<?php

namespace App\Domains\Messages\Http\Controllers;

class MessageController
{
    public function index()
    {
       return view('backend.messages.index');
    }

    public function history()
    {
       return view('backend.messages.history');
    }

}
