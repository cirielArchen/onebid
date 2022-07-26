<?php

declare(strict_types=1);

namespace App\Service;

use App\Model\CurrencyData;
use App\Model\CurrencyChange;

class CurrenciesChangeCalculator
{
    public function calculate(CurrencyData $today, CurrencyData $fromDate): array
    {
        $currencyChangeArray = [];

        foreach($today->rates as $key => $value) {
            $currencyChange = new CurrencyChange();
            $currencyChange->currency = $key;
            $currencyChange->today = $today->rates->{$key};
            $currencyChange->fromDate = $fromDate->rates->{$key};
            $currencyChange->change = abs(($fromDate->rates->{$key}/$today->rates->{$key})*100 - 100);
            $currencyChangeArray[] = $currencyChange;
        }
        
        return $currencyChangeArray;
    }
}