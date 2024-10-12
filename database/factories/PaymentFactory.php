<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Payment;
use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition()
    {
        return [
            'order_id' => $this->faker->unique()->word,
            'track_id' => $this->faker->unique()->word,
            'amount' => 1000,
            'payer_name' => $this->faker->name,
            'payer_identity' => $this->faker->email,
            'status' =>PaymentStatus::PENDING->value,
            'user_id' => User::factory(),
        ];
    }
}
