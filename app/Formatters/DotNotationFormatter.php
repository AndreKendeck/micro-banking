<?php

namespace App\Formatters;

use Money\Money;
use Money\MoneyFormatter;

class DotNotationFormatter implements MoneyFormatter
{
    public function format(Money $money): string
    {
    }
}
