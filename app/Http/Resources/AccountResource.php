<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AccountResource extends JsonResource
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
            'isNew' => $this->isNew(),
            'accountNumber' =>  $this->number,
            'closingBalance' => $this->closing_balance->formatByCurrencySymbol() 
        ];
    }
}
