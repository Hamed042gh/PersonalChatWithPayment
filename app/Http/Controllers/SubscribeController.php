<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SubscribeController extends Controller
{
    public function index()
    {
        return view('subscribe.index');
    }
}
