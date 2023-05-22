<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type' => $this->type,
            'amount' => $this->amount->formatByCurrencySymbol(),
            'description' => $this->description,
            'accountId' => $this->account_id,
            'created_at' => $this->created_at->toDateTimeString(),
            'running_balance' => $this->running_balance->formatByCurrencySymbol()
        ];
    }
}
