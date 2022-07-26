<?php

declare(strict_types=1);

namespace App\Service;

use App\Model\Currencies;
use App\Model\CurrencyData;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Serializer\SerializerInterface;
use DateTime;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpClient\Exception\ServerException;

class CurrencyApi
{
    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly SerializerInterface $serializer,
        ){
    }

    public function getCurrenciesFromDate(DateTime $date): ?CurrencyData
    {
        $response = $this->client->request(
            'GET',
            'https://api.frankfurter.app/' . $date->format('Y-m-d') .'?base=PLN&symbols=EUR,USD,GBP,CZK'
        );

        if ($response->getStatusCode() >= 400) {
            throw new ClientException($response);
        }

        if ($response->getStatusCode() >= 500) {
            throw new ServerException($response);
        }

        $currencyData = $this->mapper($response->getContent());

        return $currencyData;
    }

    private function mapper(string $json): CurrencyData
    {
        $data = json_decode($json);

        $currencyData = new CurrencyData();
        $rates = new Currencies();

        $currencyData->amount = $data->amount;
        $currencyData->base = $data->base;
        $currencyData->date = $data->date;
        $rates->czk = $data->rates->CZK;
        $rates->eur = $data->rates->EUR;
        $rates->gbp = $data->rates->GBP;
        $rates->usd = $data->rates->USD;
        $currencyData->rates = $rates;

        return $currencyData;
    }
}