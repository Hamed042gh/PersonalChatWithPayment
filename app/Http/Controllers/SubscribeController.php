<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class SubscribeController extends Controller
{
    public function index()
    {
        return Cache::remember('subscribe', 600, function () {

            return view('subscribe.index')->render();
        });
    }

    public function purchase()
    {
        $user = Auth::user();
        $orderId = Str::uuid();
        session(['order_id' => $orderId]);

        return view('subscribe.purchase', compact('user', 'orderId'));
    }
}
