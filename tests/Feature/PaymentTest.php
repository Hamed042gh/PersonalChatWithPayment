<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Payment;
use App\Enums\PaymentStatus;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PaymentTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    // 1. Test sending a payment request to a real service (Sandbox)
    public function test_payment_with_real_service_sandbox()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->fakeRequest();

        // Send payment request
        $response = $this->requestToZibal($user);

        // Check for redirect to Zibal
        $response->assertRedirect('https://gateway.zibal.ir/start/testTrackId123');

        // Check in Database
        $this->assertDatabaseHasPayment($user);
    }

    protected function fakeRequest()
    {
        Http::fake([
            'https://gateway.zibal.ir/v1/request' => Http::response([
                'trackId' => 'testTrackId123',
            ], 200),
        ]);
    }

    protected function assertDatabaseHasPayment($user)
    {
        $this->assertDatabaseHas('payments', [
            'order_id' => 'order123',
            'track_id' => 'testTrackId123',
            'amount' => 1000,
            'payer_name' => 'Hamed Ghasemi',
            'payer_identity' => $user->email,
            'status' => PaymentStatus::PENDING->value,
            'user_id' => $user->id,
        ]);
    }

    protected function requestToZibal($user)
    {
        return $this->post('/payment/request', [
            'amount' => 1000,
            'order_id' => 'order123',
            'payerIdentity' => $user->email,
            'payerName' => 'Hamed Ghasemi',
            'description' => 'Payment for order 123',
            'user_id' => $user->id,
        ]);
    }

    // 2. Test payment verification method
    public function test_verify_payment_method()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->createPayment($user);
        session(['order_id' => 'order123']);

        $this->fakeResponse();
        $response = $this->processPayment(1, PaymentStatus::SUCCESS_CONFIRMED->value);

        // Check redirection and database updates
        $response->assertRedirect('/dashboard');
        $this->assertPaymentDatabase($user, PaymentStatus::SUCCESS_CONFIRMED->value);
    }

    // 3. Test failed payment method
    public function test_failed_payment_method()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $this->createPayment($user);
        session(['order_id' => 'order123']);
        $this->fakeFailureResponse();

        $response = $this->processPayment(0, PaymentStatus::INTERNAL_ERROR->value);

        // Check redirection and database updates
        $response->assertRedirect('/dashboard');
        $this->assertPaymentDatabase($user, PaymentStatus::INTERNAL_ERROR->value);
    }

    // Factory method to create a payment record
    protected function createPayment($user)
    {
        Payment::factory()->create([
            'track_id' => 'testTrackId123',
            'order_id' => 'order123',
            'amount' => 1000,
            'payer_name' => $user->name,
            'payer_identity' => $user->email,
            'status' => PaymentStatus::PENDING->value,
            'user_id' => $user->id,
        ]);
    }

    // Method to handle payment processing
    protected function processPayment($success, $status)
    {
        return $this->post('/payment/verify', [
            'success' => $success,
            'trackId' => 'testTrackId123',
            'status' => $status,
        ]);
    }

    // Method to fake the success response from the payment gateway
    protected function fakeResponse()
    {
        Http::fake([
            'https://gateway.zibal.ir/v1/verify' => Http::response([
                'result' => 100,
                'status' => PaymentStatus::SUCCESS_CONFIRMED->value,
            ], 200),
        ]);
    }

    // Method to fake the failure response from the payment gateway
    protected function fakeFailureResponse()
    {
        Http::fake([
            'https://gateway.zibal.ir/v1/verify' => Http::response([
                'result' => 202, // Example result for failure
                'status' => PaymentStatus::INTERNAL_ERROR->value,
            ], 200),
        ]);
    }

    // Assert payment database updates
    protected function assertPaymentDatabase($user, $status)
    {
        $this->assertDatabaseHas('payments', [
            'track_id' => 'testTrackId123',
            'status' => $status,
        ]);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'unlimited_message' => $status === PaymentStatus::SUCCESS_CONFIRMED->value,
        ]);
    }
}
