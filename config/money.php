<?php

use Cknow\Money\Money;

return [
    /*
     |--------------------------------------------------------------------------
     | Laravel money
     |--------------------------------------------------------------------------
     */
    'locale' => config('app.locale', 'en_ZA'),
    'defaultCurrency' => config('app.currency', 'ZAR'),
    'defaultFormatter' => null,
    'isoCurrenciesPath' => __DIR__ . '/../vendor/moneyphp/money/resources/currency.php',
    'currencies' => [
        'iso' => 'all',
        'bitcoin' => 'all',
        'custom' => [],
    ],
];
