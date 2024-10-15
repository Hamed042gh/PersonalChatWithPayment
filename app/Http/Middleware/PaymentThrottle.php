<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Payment;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PaymentThrottle
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()) {

            $userPaymentCount = Payment::where('user_id', $request->user()->id)
                ->where('created_at', '>=', now()->subDay())
                ->count();
            if ($userPaymentCount >= 3) {
                $request->session()->flash('error', 'You have reached the daily limit of 3 payments. Please try again after 24 hours.');



                return redirect()->route('dashboard');
            }
        }
        return $next($request);
    }
}
