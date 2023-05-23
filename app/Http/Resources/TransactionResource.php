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
            'amount' => $this->amount->format(style: \NumberFormatter::CURRENCY),
            'description' => $this->description,
            'accountId' => $this->account_id,
            'createdAt' => $this->created_at->toDateTimeString(),
            'openingBalance' => $this->opening_balance->format(style: \NumberFormatter::CURRENCY),
            'runningBalance' => $this->running_balance->format(style: \NumberFormatter::CURRENCY),
        ];
    }
}
