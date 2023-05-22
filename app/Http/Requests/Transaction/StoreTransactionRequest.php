<?php

namespace App\Http\Requests\Transaction;

use App\Enums\TransactionType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTransactionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'type' => [
                'required',
                Rule::in(array_map(fn (TransactionType $type) => $type->value, TransactionType::cases()))
            ],
            'amount' => ['numeric', 'min:0'],
            'account_number' => ['min:9', 'max:9', 'string', 'required', Rule::exists('accounts', 'number')->where('user_id', auth()->id())]
        ];
    }
}
