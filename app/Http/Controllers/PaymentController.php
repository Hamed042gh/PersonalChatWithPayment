<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Enums\PaymentStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\PaymentRequestStore;

class PaymentController extends Controller
{
    private const ZIBAL_API_REQUEST = 'https://gateway.zibal.ir/v1/request';
    private const ZIBAL_API_INQUIRY = 'https://gateway.zibal.ir/v1/inquiry';
    private const CALLBACK_URL = 'http://localhost/payment/callback';

    public function requestPayment(PaymentRequestStore $request)
    {
        $zibalResponse = $this->sendRequestToZibal($request);

        if (isset($zibalResponse['trackId'])) {
            $this->storePayment($request, $zibalResponse['trackId']);

            return redirect('https://gateway.zibal.ir/start/' . $zibalResponse['trackId']);
        }

        return back()->withErrors(['payment' => 'Error in payment system!']);
    }

    private function sendRequestToZibal(PaymentRequestStore $request)
    {
        return Http::post(self::ZIBAL_API_REQUEST, [
            'merchant' => 'zibal',
            'amount' => 1000,
            'orderId' => $request->input('order_id'),
            'callbackUrl' => self::CALLBACK_URL,
            'description' => 'Order payment',
        ])->json();
    }


    private function storePayment(PaymentRequestStore $request, string $trackId)
    {

        Payment::create([
            'order_id' => $request->input('order_id'),
            'track_id' => $trackId,
            'amount' => 1000,
            'payer_name' => $request->input('payerName'),
            'payer_identity' => $request->input('payerIdentity'),
            'status' => PaymentStatus::PENDING->value,
            'user_id' => Auth::id(),
        ]);
    }


    public function verifyPayment(Request $request)
    {
        $trackId = $request->input('trackId');
        $success = $request->input('success');
        $status = $request->input('status');
        $orderId = session('order_id');

        if ($success == '1') {
            $verifyResponse = $this->verifyPaymentWithZibal($trackId);

            if ($verifyResponse['result'] == 100) {
                $status = $verifyResponse['status'];
                return $this->handleSuccessfulPayment($trackId, $status, $orderId);
            }

            return redirect('/dashboard')->withErrors(['payment' => 'Payment confirmed but verification failed.']);
        }

        return redirect('/dashboard')->withErrors(['payment' => 'Payment failed.']);
    }

    protected function verifyPaymentWithZibal($trackId)
    {
        $response = Http::post('https://gateway.zibal.ir/v1/verify', [
            'merchant' => 'zibal',
            'trackId' => $trackId,
        ])->json();
        Log::info($response);
        return $response;
    }


    protected function handleSuccessfulPayment($trackId, $status, $orderId)
    {

        $payment = Payment::where('track_id', $trackId)->first();
       
        if ($payment) {
            $payment->status = $status;
           
            $payment->save();
        }
      
        return $this->handlePaymentStatus($status, $orderId);
    }


    protected function handlePaymentStatus(int $status, string $orderId)
    {
        
        switch (PaymentStatus::from($status)) {
            case PaymentStatus::SUCCESS_CONFIRMED:
                return redirect('/dashboard')->with('message', 'Payment successful and confirmed. Order ID: ' . $orderId);
            case PaymentStatus::SUCCESS_UNCONFIRMED:
                return redirect('/dashboard')->withErrors(['payment' => 'Payment successful but not yet confirmed. Order ID: ' . $orderId]);
            case PaymentStatus::CANCELED_BY_USER:
                return redirect('/dashboard')->withErrors(['payment' => 'Payment canceled by the user.']);
            case PaymentStatus::PENDING:
                return redirect('/dashboard')->withErrors(['payment' => 'Payment is pending.']);
            case PaymentStatus::INTERNAL_ERROR:
                return redirect('/dashboard')->withErrors(['payment' => 'An internal error occurred.']);
            default:
                return redirect('/dashboard')->withErrors(['payment' => 'Unknown payment status.']);
        }
    }
}
