<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Enums\PaymentStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Http\Requests\PaymentRequestStore;

class PaymentController extends Controller
{


    public function requestPayment(PaymentRequestStore $request)
    {
      
        $trackId = uniqid(); 
        $ZIBAL_API_REQUEST = 'https://gateway.zibal.ir/v1/request';
        $sendRequestToZibal = Http::post($ZIBAL_API_REQUEST, [
            'merchant' => 'zibal',
            'amount' => 1000,
            'order_id' => $request->input('order_id'),
            'payerName' => $request->input('payerName'),
            'callbackUrl' => 'http://localhost/payment/callback'
        ]);

        $response = json_decode($sendRequestToZibal->body(), true);

        if (isset($response['trackId'])) {

            Payment::create([
                'order_id' => $request->input('order_id'),
                'track_id' => $response['trackId'],
                'amount' => 1000,
                'payer_name' => $request->input('payerName'),
                'payer_identity' => $request->input('payerIdentity'), // یا هر فیلدی که برای شناسایی کاربر استفاده می‌کنید
                'status' => 'pending',
                'user_id' => Auth::user()->id,
            ]);
            $trackId = $response['trackId'];

            return redirect('https://gateway.zibal.ir/start/' . $trackId);
        } else {

            return back()->withErrors(['payment' => 'error in payment system!!']);
        }
    }


    public function verifyPayment(Request $request)
    {

        $trackId = $request->query('trackId');
        $success = $request->query('success');
        $status = $request->query('status');
        $orderId = session('order_id');

        if ($success == '1') {

            $inquiryResponse = $this->inquiryPayment($trackId);

            if ($inquiryResponse['result'] == 100) {

                $payment = Payment::where('track_id', $trackId)->first();
                $payment->status = $status;
                $payment->save();

                return $this->handlePaymentStatus($status, $orderId);
            } else {
                return redirect('/dashboard')->withErrors(['payment' => 'Payment was confirmed but inquiry failed.']);
            }
        } else {
            return redirect('/dashboard')->withErrors(['payment' => 'Payment failed.']);
        }
    }

    private function handlePaymentStatus(int $status, string $orderId)
    {


        switch (PaymentStatus::from($status)) {
            case PaymentStatus::SUCCESS_CONFIRMED:
                return redirect('/dashboard')->with('message', 'Payment was successful and confirmed. Order ID: ' . $orderId);
            case PaymentStatus::SUCCESS_UNCONFIRMED:
                return redirect('/dashboard')->withErrors(['payment' => 'Payment was successful but not yet confirmed. Order ID: ' . $orderId]);
            case PaymentStatus::CANCELED_BY_USER:
                return redirect('/dashboard')->withErrors(['payment' => 'Payment was canceled by the user.']);
            case PaymentStatus::PENDING:
                return redirect('/dashboard')->withErrors(['payment' => 'Payment is pending.']);
            case PaymentStatus::INTERNAL_ERROR:
                return redirect('/dashboard')->withErrors(['payment' => 'An internal error occurred.']);

            default:
                return redirect('/dashboard')->withErrors(['payment' => 'Unknown payment status.']);
        }
    }

    private function inquiryPayment($trackId)
    {
        $ZIBAL_API_INQUIRY = 'https://gateway.zibal.ir/v1/inquiry';

        $inquiryRequest = Http::post($ZIBAL_API_INQUIRY, [
            'merchant' => 'zibal',
            'trackId' => $trackId
        ]);
        $inquiryRequest = json_decode($inquiryRequest->body(), true);

        return $inquiryRequest;
    }
}
