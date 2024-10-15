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


        Http::fake([
            'https://gateway.zibal.ir/v1/request' => Http::response([
                'trackId' => 'testTrackId123',
            ], 200),
        ]);

        //send payment request
        $response = $this->post('/payment/request', [
            'amount' => 1000,
            'order_id' => 'order123',
            'payerIdentity' => $user->email,
            'payerName' => 'Hamed Ghasemi',
            'description' => 'Payment for order 123',
            'user_id' => $user->id,
        ]);

        //checking for redirect to Zibal
        $response->assertRedirect('https://gateway.zibal.ir/start/testTrackId123');

        //checking in DataBase
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

    // 2. Test payment verification method
    public function test_verify_payment_method()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Payment::factory()->create([
            'track_id' => 'testTrackId123',
            'order_id' => 'order123',
            'amount' => 1000,
            'payer_name' => $user->name,
            'payer_identity' => $user->email,
            'status' => PaymentStatus::PENDING->value,
            'user_id' => $user->id,
        ]);
        session(['order_id' => 'order123']);

        Http::fake([
            'https://gateway.zibal.ir/v1/verify' => Http::response([
                'result' => 100,
                'status' => PaymentStatus::SUCCESS_CONFIRMED->value,
            ], 200),
        ]);

        $response = $this->post('/payment/verify', [
            'success' => 1,
            'trackId' => 'testTrackId123',
            'status' => PaymentStatus::SUCCESS_CONFIRMED->value
        ]);

        $response->assertRedirect('/dashboard');

        $this->assertDatabaseHas('payments', [
            'track_id' => 'testTrackId123',
            'status' => PaymentStatus::SUCCESS_CONFIRMED->value,
        ]);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'unlimited_message' => true,
        ]);
    }

    // 3. Test failed payment method
    public function test_failed_payment_method()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        Payment::factory()->create([
            'track_id' => 'testTrackId123',
            'order_id' => 'order123',
            'amount' => 1000,
            'payer_name' => $user->name,
            'payer_identity' => $user->email,
            'status' => PaymentStatus::PENDING->value,
            'user_id' => $user->id,
        ]);
        session(['order_id' => 'order123']);
        Http::fake([
            'https://gateway.zibal.ir/v1/verify' => Http::response([
                'result' => 202,
                'status' => PaymentStatus::INTERNAL_ERROR->value,
            ], 200),
        ]);
        $response = $this->post('/payment/verify', [
            'success' => 0,
            'trackId' => 'testTrackId123',
            'status' => PaymentStatus::INTERNAL_ERROR->value
        ]);

        $response->assertRedirect('/dashboard');

        $this->assertDatabaseHas('payments', [
            'track_id' => 'testTrackId123',
            'status' => PaymentStatus::INTERNAL_ERROR->value,
        ]);
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'unlimited_message' => false,
        ]);
    }
}
