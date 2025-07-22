<?php

namespace App\Service;

use App\Entity\Currency;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CurrencyService
{
    public function __construct(private HttpClientInterface $client) {}

    public function fetchRates(): array
    {
        $response = $this->client->request(
            'GET',
            'https://api.nbp.pl/api/exchangerates/tables/A?format=json'
        );

        $data = $response->toArray();
        return $data[0]['rates'] ?? [];
    }

    public function syncCurrencies(EntityManagerInterface $em): void
    {
        $rates = $this->fetchRates();

        foreach ($rates as $rate) {
            $currency = $em->getRepository(Currency::class)
                ->findOneBy(['currency_code' => $rate['code']]);

            if (!$currency) {
                $currency = new Currency();
                $currency->setCurrencyCode($rate['code']);
                $currency->setName($rate['currency']);
                $em->persist($currency);

                error_log('Creating new currency: ' . $currency->getId() . 
                     ' - ' . $currency->getCurrencyCode());
            }

            $currency->setExchangeRate($rate['mid']);
        }
        dump($em->getUnitOfWork()->getScheduledEntityInsertions());
        $em->flush();
    }

}