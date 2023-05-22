<?php

namespace App\Http\Requests\Account;

use App\Enums\AccountType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAccountRequest extends FormRequest
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
            'name' => ['required', 'string', 'min:3', 'max:100'],
            'type' => ['required', Rule::in(
                array_map(fn (AccountType $type) => $type->value, AccountType::cases())
            )]
        ];
    }

    /**
     * @return array
     */
    public function messages(): array
    {
        return [
            'type.in' => 'The selected account type is invalid, please select one of CHEQ, SAVINGS or CREDIT account types.'
        ];
    }
}
