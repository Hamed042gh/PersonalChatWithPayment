<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Cache;

class MessageController extends Controller
{
  public function index()
  {

    return Cache::remember('ChatPanel', 600, function () {

      return view('message.index')->render();
    });
  }
}
