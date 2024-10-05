<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UsersPaymentLimit
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        $payment = Payment::where('user_id', $user->id)->first();
        if ($payment  &&  $payment->status == 2) {
            return redirect('/dashboard')->withErrors(['message' => 'You already made a payment.']);
        }
        return $next($request);
    }
}
