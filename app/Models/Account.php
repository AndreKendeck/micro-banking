<?php

namespace App\Models;

use App\Enums\AccountType;
use App\Enums\TransactionType;
use App\Exceptions\InsufficientFundsException;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Cknow\Money\Money;
use Illuminate\Support\Collection;

/**
 * @property int string
 * @property string name
 * @property string type
 * @property int user_id
 * @property string number
 * @property User user
 * @property Collection transactions
 * @property Carbon created_at
 * @property Carbon updated_at
 * @property Money opening_balance
 * @property Money closing_balance
 * @method static Builder byType(AccountType $accountType)
 * @method static Builder byNumber(string $accountNumber)
 */
class Account extends Model
{
    use HasFactory;

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var array
     */
    protected $casts = [
        'type' => 'string',
        'user_id' => 'integer',
        'number' => 'string',
    ];

    /**
     * @var array
     */
    protected $appends = [
        'closing_balance',
        'opening_balance'
    ];

    /**
     * Get the closing balance of the account
     *
     * @return Attribute
     */
    protected function closingBalance(): Attribute
    {
        return new Attribute(
            get: fn () => money_sum(...$this->transactions->pluck('amount'))
        );
    }


    /**
     * Get the opening balance of the account
     * The initial balance balance should be a heavy calculation at 
     * first then we should get the balance for the last 30 days
     * @return Attribute
     */
    protected function openingBalance(): Attribute
    {
        return new Attribute(
            get: function () {
                return $this->transactions->first()->amount;
            }
        );
    }

    /**
     * Generate an account number
     * @return string
     */
    protected static function generateAccountNumber(): string
    {
        $result = [];
        for ($i = 0; $i < 9; $i++) {
            array_push($result, rand(0, 9));
        }
        return implode("", $result);
    }

    /**
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->number = self::generateAccountNumber();
        });
    }

    /**
     * Get the account by type ::byType('SAVINGS')
     *
     * @param Builder $query
     * @param AccountType $accountType
     * @return void
     */
    public function scopeByType(Builder $query, AccountType $accountType): void
    {
        $query->where('type', $accountType->value);
    }

    /**
     * Get the account by number
     *
     * @param Builder $query
     * @param string $accountNumber
     * @return void
     */
    public function scopeByNumber(Builder $query, string $accountNumber): void
    {
        $query->where('number', $accountNumber);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactions(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * --------
     * For better developer experience its better to 
     * have seprate debit and credit methods instead of having one transaction 
     * for handling debits and credit
     * ---------
     */

    /**
     * @param integer|float $amount
     * @param string $description
     * @param Carbon|null $date
     * @return Transaction
     */
    public function credit(int|float $amount, string $description, Carbon $date = null): Transaction
    {
        return $this->transactions()->save(new Transaction([
            'type' => TransactionType::CREDIT->value,
            'amount' => money($amount),
            'description' => $description,
            'created_at' => $date ? $date : now()
        ]));
    }

    /**
     * @param integer|float $amount
     * @param string $description
     * @param Carbon $date
     * @return Transaction
     */
    public function debit(int|float $amount, string $description, Carbon $date = null): Transaction
    {
        if (!$this->isCredit() && $this->incomingAmountIsLargerThanClosingBalance($amount)) {
            // throw new InsufficientFundsException();
        }
        return $this->transactions()->save(new Transaction([
            'type' => TransactionType::DEBIT->value,
            'amount' => money($amount),
            'description' => $description,
            'created_at' => $date ? $date : now()
        ]));
    }

    /**
     * @param Carbon|null $date
     * @return Money
     */
    public function getOpeningBalance(Carbon $date = null): Money
    {
        return money_sum(
            ...$this->transactions()->whereDate('created_at', '<=', $date->toDateString())->get()->pluck('amount')
        );
    }


    /**
     * If the account has been opened in the last 30 days
     *
     * @return boolean
     */
    public function isNew(): bool
    {
        return $this->created_at->gt(now()->subDays(30));
    }


    /**
     * @return boolean
     */
    public function isCredit(): bool
    {
        return AccountType::isCredit($this->type);
    }

    /**
     * @return boolean
     */
    public function isCheq(): bool
    {
        return AccountType::isCheq($this->type);
    }


    /**
     * @return boolean
     */
    public function isSavings(): bool
    {
        return AccountType::isSavings($this->type);
    }

    /**
     * @param integer|float $amount
     * @return boolean
     */
    protected function incomingAmountIsLargerThanClosingBalance(int|float $amount): bool
    {
        return (new Money($amount))->greaterThan($this->closing_balance);
    }
}
