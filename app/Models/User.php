<?php

namespace App\Models;

use App\Casts\Money;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Collection;

/**
 * @property int id
 * @property string name
 * @property string email
 * @property Collection accounts
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * @var array
     */
    protected $guarded = [];

    /**
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @var array
     */
    protected $appends = [
        'equity'
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
        'name' => 'string',
        'email' => 'string',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function accounts(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Account::class)->latest();
    }

    /**
     * @param Builder $query
     * @param string $email
     * @return void
     */
    public function scopeByEmail(Builder $query, string $email): void
    {
        $query->where('email', $email);
    }


    /**
     * Get the sum of all the closing balances of the accounts
     * @return Attribute
     */
    protected function equity(): Attribute
    {
        return new Attribute(
            get: function () {
                return money_sum(...$this->accounts->pluck('closing_balance'));
            }
        );
    }
}
