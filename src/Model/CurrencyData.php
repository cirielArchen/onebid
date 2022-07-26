<?php

declare(strict_types=1);

namespace App\Model;

use DateTime;

class CurrencyData
{
    public float $amount;
    public string $base;
    public string $date;
    public Currencies $rates;
}