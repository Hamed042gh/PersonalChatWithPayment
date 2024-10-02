<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscribeController extends Controller
{
    public function index()
    {
        return view('subscribe.index');
    }

    public function purchase()
    {
        $user = Auth::user();
        $orderNumber = Str::uuid();

        return view('subscribe.purchase', compact('user', 'orderNumber'));
    }
}
