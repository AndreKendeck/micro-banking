<?php

namespace App\Http\Resources;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class AccountResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $year = $request->query('year', now()->year);
        $month = $request->query('month', now()->month);
        $date = Carbon::create($year, $month);
        return [
            'id' => $this->id,
            'type' => $this->type,
            'name' => $this->name,
            'isNew' => $this->isNew(),
            'accountNumber' =>  $this->number,
            'openingBalance' => $this->opening_balance->format(style: \NumberFormatter::CURRENCY),
            'closingBalance' => $this->closing_balance->format(style: \NumberFormatter::CURRENCY),
            'transactions' =>  $this->groupMonthlyTransactions($date)
        ];
    }
}
