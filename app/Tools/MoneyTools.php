<?php


namespace BoShop\Tools;


use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Formatter\IntlMoneyFormatter;
use Money\Money;

class MoneyTools
{

    public static function format($price): string {
        $money = new Money($price * 100, new Currency("CZK"));
        $currencies = new ISOCurrencies();

        $numberFormatter = new \NumberFormatter("cs_CZ", \NumberFormatter::CURRENCY);
        $moneyFormatter = new IntlMoneyFormatter($numberFormatter, $currencies);

        return $moneyFormatter->format($money);
    }
}