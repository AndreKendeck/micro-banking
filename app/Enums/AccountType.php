<?php

namespace App\Enums;


enum AccountType: string
{
    case SAVINGS = 'SAVINGS';
    case CHEQ = 'CHEQ';
    case CREDIT = 'CREDIT';

    /**
     * @param string $type
     * @return boolean
     */
    public static function isCredit(string $type): bool
    {
        return $type === self::CREDIT->value;
    }

    /**
     * @param string $type
     * @return boolean
     */
    public static function isSavings(string $type): bool
    {
        return $type === self::SAVINGS->value;
    }


    /**
     * @param string $type
     * @return boolean
     */
    public static function isCheq(string $type): bool
    {
        return $type === self::CHEQ->value;
    }
}
