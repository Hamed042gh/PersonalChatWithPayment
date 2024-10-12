<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRequestStore extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'amount' => 'required|integer|in:1000',

            'order_id' => 'required|string',

            'user_id' => 'required|exists:users,id',

            'payerIdentity' => 'required|string|email',

            'payerName' => 'required|string',

            'description' => 'required|string',
        ];
    }
    public function messages()
    {
        return [
            'amount.required' => 'The amount is required.',
            'amount.in' => 'The amount must be 1000 Toman.',
            'order_id.required' => 'The order ID is required.',
            'user_id.required' => 'The user ID is required.',
            'payerIdentity.required' => 'The payer email is required.',
            'payerIdentity.email' => 'The payer email format must be valid.',
            'payerName.required' => 'The payer name is required.',
            'description.required' => 'The payment description is required.',
        ];
    }
    
}
