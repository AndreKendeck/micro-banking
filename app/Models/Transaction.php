<?php

namespace App\Models;

use App\Casts\Money;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Cknow\Money\Money as CMoney;

/**
 * @property string id
 * @property CMoney amount
 * @property string description
 * @property integer account_id
 * @property Account account
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

    /**
     * @var array
     */
    protected $guarded = [];


    /**
     * @var array
     */
    protected $casts = [
        'id' => 'string',
        'type' => 'string',
        'description' => 'string',
        'account_id' => 'integer',
        'amount' => Money::class
    ];

    /**
     * @var array
     */
    protected $dates = [
        'voided_at',
        'created_at',
        'updated_at'
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
}
