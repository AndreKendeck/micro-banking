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
use Illuminate\Support\Facades\Log;

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
 * @method static Builder byUser(User $user)
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
        'id' => 'integer',
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
     * @return Attribute
     */
    protected $dates = [
        'created_at',
        'updated_at'
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
     * @param Builder $builder
     * @param User $user
     * @return void
     */
    public function scopeByUser(Builder $query, User $user): void
    {
        $query->where('user_id', $user->id);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

   /**
    * @param Carbon|null $period
    * @param boolean $audit
    * @return \Illuminate\Database\Eloquent\Relations\HasMany
    */
    public function transactions(Carbon $period = null): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        $query = $this->hasMany(Transaction::class)->orderBy('created_at', 'ASC');
        if ($period) {
            $query->where([
                ['created_at', '>=', $period->startOfMonth()->getTimestamp()],
                ['created_at', '<=', $period->endOfMonth()->getTimestamp()]
            ]);
        }
        return $query;
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
    public function credit(Money $amount, string $description, Carbon $date = null): Transaction
    {
        return $this->transactions()->save(new Transaction([
            'type' => TransactionType::CREDIT->value,
            'amount' => $amount,
            'description' => $description,
            'created_at' => $date ? $date->getTimestamp() : time(),
            'updated_at' => $date ? $date->getTimestamp() : time()
        ]));
    }

    /**
     * @param Money $amount
     * @param string $description
     * @param Carbon|null $date
     * @throws InsufficientFundsException
     * @return Transaction
     */
    public function debit(Money $amount, string $description, Carbon $date = null): Transaction
    {
        try {
            if (!$this->isCredit() && $this->incomingAmountIsLargerThanClosingBalance($amount)) {
                throw new InsufficientFundsException();
            }
            return $this->transactions()->save(new Transaction([
                'type' => TransactionType::DEBIT->value,
                'amount' => $amount,
                'description' => $description,
                'created_at' => $date ? $date->getTimestamp() : time(),
                'updated_at' => $date ? $date->getTimestamp() : time()
            ]));
        } catch (InsufficientFundsException $e) {
            Log::critical("Could not debit amount of {$amount->formatByCurrencySymbol()} on account due to Insufficient Funds.");
            return $this->transactions()->save(new Transaction([
                'type' => TransactionType::DEBIT->value,
                'amount' => $amount,
                "description" => "Failed to debit amount {$amount->formatByCurrencySymbol()} on Account due to  Insufficient Funds.",
                'voided_at' => now()->getTimestamp(),
                'created_at' => $date ? $date->getTimestamp() : time(),
                'updated_at' => $date ? $date->getTimestamp() : time()
            ]));
        }
    }

    /**
     * @param Carbon $date
     * @return Money
     */
    public function getOpeningBalance(Carbon $date): Money
    {
        return money_sum(
            money(0),
            ...$this->transactions()
                ->where('created_at', '<=', $date->getTimestamp())
                ->get()
                ->pluck('amount')
        );
    }

    /**
     * @param Carbon $period
     * @return Collection
     */
    public function groupMonthlyTransactions(Carbon $period): Collection
    {
        return $this->transactions($period)->get()
            ->groupBy(fn (Transaction $transaction) => $transaction->created_at->format('Y-m-d'))
            ->map(fn (Collection $dailyTransactions) => [
                'totalDebits' => $dailyTransactions->filter(fn (Transaction $transaction) => $transaction->isDebit())->count(),
                'totalCredits' => $dailyTransactions->filter(fn (Transaction $transaction) => $transaction->isCredit())->count(),
                'netMovement' => money_sum(money(0), ...$dailyTransactions->pluck('amount'))->format(style: \NumberFormatter::CURRENCY),
                'openingBalance' => money_sum(money(0), ...$dailyTransactions->pluck('opening_balance'))->format(style: \NumberFormatter::CURRENCY),
                'closingBalance' => money_sum(money(0), ...$dailyTransactions->pluck('running_balance'))->format(style: \NumberFormatter::CURRENCY),
            ]);
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
     * @param Money $amount
     * @return boolean
     */
    protected function incomingAmountIsLargerThanClosingBalance(Money $amount): bool
    {
        return $amount->absolute()->greaterThan($this->closing_balance);
    }
}
