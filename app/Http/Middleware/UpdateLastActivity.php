<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UpdateLastActivity
{

    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            try {
                Auth::user()->update(['last_activity' => now()]);
            } catch (\Exception $e) {
                Log::error('Error updating last_activity: ' . $e->getMessage());
            }
        }

        return $next($request);
    }
}
