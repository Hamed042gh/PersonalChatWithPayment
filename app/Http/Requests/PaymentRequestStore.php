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
            'amount.required' => 'مبلغ باید وارد شود.',
            'amount.in' => 'مبلغ باید 1000 تومان باشد.',
            'order_id.required' => 'شناسه سفارش باید وارد شود.',
            'user_id.required' => 'شناسه کاربر لازم است.',
            'payerIdentity.required' => 'ایمیل پرداخت‌کننده لازم است.',
            'payerIdentity.email' => 'فرمت ایمیل پرداخت‌کننده باید معتبر باشد.',
            'payerName.required' => 'نام پرداخت‌کننده باید وارد شود.',
            'description.required' => 'توضیحات پرداخت باید وارد شود.',
        ];
    }
}
