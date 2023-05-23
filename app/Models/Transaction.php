<?php

namespace App\Models;

use App\Casts\Money;
use App\Enums\TransactionType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cknow\Money\Money as CMoney;
use Illuminate\Database\Eloquent\Casts\Attribute;
use \Illuminate\Database\Eloquent\Builder;

/**
 * @property string id
 * @property CMoney amount
 * @property string description
 * @property integer account_id
 * @property Account account
 * @property CMoney running_balance
 * @property Cmoney opening_balance
 * @property Carbon created_at
 * @property Carbon updated_at
 */
class Transaction extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;

    /**
     * @var string|null 
     */
    const DELETED_AT = 'voided_at';


    public $timestamps = false;


    /**
     * Since transactions happen in micro seconds of each other
     * @var string
     */
    protected $dateFormat = 'U';

    /**
     * @var array
     */
    protected $guarded = [];


    /**
     * @var array
     */
    protected $appends = [
        'running_balance',
        'opening_balance'
    ];


    /**
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'type' => 'string',
        'description' => 'string',
        'account_id' => 'integer',
        'amount' => Money::class,
        'created_at' => 'date:Y-m-d',
        'updated_at' => 'date:Y-m-d'
    ];

    /**
     * @return void
     */
    public function void(): bool
    {
        return $this->update(['voided_at' => now()]);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function account(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Account::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function olderTransactions(): \Illuminate\Database\Eloquent\Builder
    {
        return self::where('account_id', $this->account_id)
            ->where('id', '<', $this->id);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePerDay(Builder $query, Carbon $day): void
    {
        $query->whereDay('created_at', $day);
    }

    /**
     * This is the balance of the account before the transaction occurs
     * @return Attribute
     */
    protected function openingBalance(): Attribute
    {
        return new Attribute(
            get: fn () => money_sum(new CMoney(0), ...$this->olderTransactions()->get()->pluck('amount'))
        );
    }

    /**
     * This is the balance of the account after the transaction occurs
     * @return Attribute
     */
    protected function runningBalance(): Attribute
    {
        return new Attribute(
            get: fn () => money_sum($this->amount, ...$this->olderTransactions()->get()->pluck('amount'))
        );
    }

    /**
     * @return boolean
     */
    public function isDebit(): bool
    {
        return TransactionType::from($this->type) === TransactionType::DEBIT;
    }

    /**
     * @return boolean
     */
    public function isCredit(): bool
    {
        return TransactionType::from($this->type) === TransactionType::CREDIT;
    }

    /**
     * @return boolean
     */
    public function isVoid(): bool
    {
        return !is_null($this->voided_at);
    }
}
