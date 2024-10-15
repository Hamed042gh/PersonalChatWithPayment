<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Message;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DailyChatThrottle
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if ($request->user()) {

            $messagesCount = Message::where('sender_id', $request->user()->id)
                ->where('created_at', '>=', now()->subDay())
                ->count();


            if ($messagesCount >= 7) {
                $request->session()->flash('error', 'You have reached the daily limit of 50 messages.');


                return redirect()->route('dashboard');
            }
        }

        return $next($request);
    }
}
