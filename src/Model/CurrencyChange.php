<?php

declare(strict_types=1);

namespace App\Model;

class CurrencyChange
{
    public string $currency;
    public float $today;
    public float $fromDate;
    public float $change;
}