<?php

namespace App;

use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Formatter\IntlMoneyFormatter;
use Money\Money;

class Helper
{
    public static function extractNumbersFromString(?string $value): int
    {
        return (int) preg_replace('/[^0-9]/', '', trim($value ?? ''));
    }

    public static function toPhone(string $value, bool $withCountryCode = false): string
    {
        $value = self::extractNumbersFromString($value);

        if ($withCountryCode) {
            $value = '+55' . $value;
        } else {
            $value = '+' . $value;
        }

        return $value;
    }

    public static function toMoney(int $value): string
    {
        $money = new Money($value, new Currency('BRL'));
        $currencies = new ISOCurrencies();

        $numberFormatter = new \NumberFormatter('pt-br', \NumberFormatter::CURRENCY);
        $moneyFormatter = new IntlMoneyFormatter($numberFormatter, $currencies);

        return str_replace("\xc2\xa0", " ", $moneyFormatter->format($money));
    }

    public static function toDecimal(int $value): string
    {
        $value = $value / 100;

        return number_format($value, 2, ',', '.');
    }
}
