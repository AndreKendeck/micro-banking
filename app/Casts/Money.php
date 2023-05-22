<?php

namespace App\Casts;

use App\Enums\TransactionType;
use App\Models\Transaction;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Cknow\Money\Money as CMoney;

class Money implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if ($model instanceof Transaction) {
            switch (TransactionType::from($model->type)) {
                case TransactionType::CREDIT:
                    return new CMoney($value);
                case TransactionType::DEBIT:
                    return new CMoney(-$value);
            }
        }
        return new CMoney($value);
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  array<string, mixed>  $attributes
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        return $value->getAmount();
    }
}
