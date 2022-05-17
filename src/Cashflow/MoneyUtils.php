<?php

declare(strict_types=1);

namespace Cashflow;

use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Formatter\DecimalMoneyFormatter;
use Money\Parser\DecimalMoneyParser;

class MoneyUtils
{
    public static function fromFloat(float $amount): \Money\Money
    {
        $currencies = new ISOCurrencies();
        $moneyParser = new DecimalMoneyParser($currencies);

        return $moneyParser->parse((string)$amount, new Currency('EUR'));
    }

    public static function toFloat(\Money\Money $money): float
    {
        $currencies = new ISOCurrencies();
        $moneyFormatter = new DecimalMoneyFormatter($currencies);

        return (float)$moneyFormatter->format($money);
    }
}