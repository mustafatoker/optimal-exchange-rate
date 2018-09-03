<?php

namespace ExchangeRateBundle\Services;

use Doctrine\ORM\EntityManager;
use ExchangeRateBundle\Entity\ExchangeRate;

final class ExchangeRateRegistrar
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * ExchangeRateRegistrar constructor.
     *
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function register($rates)
   {
       foreach ($rates as $parity => $rate)
       {
           $exchangeRate = new ExchangeRate();
           $exchangeRate->setSymbol($parity);
           $exchangeRate->setProvider($rate['provider']);
           $exchangeRate->setAmount($rate['amount']);

           $this->entityManager->persist($exchangeRate);
           $this->entityManager->flush();
       }
   }
}
