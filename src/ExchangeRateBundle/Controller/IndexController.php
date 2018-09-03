<?php

namespace ExchangeRateBundle\Controller;

use ExchangeRateBundle\Entity\ExchangeRate;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class IndexController extends Controller
{
    /**
     * @Route("/", name="exchange.homepage")
     */
    public function indexAction()
    {
        $currencyRates = $this->getDoctrine()
            ->getRepository(ExchangeRate::class)
            ->getLatestCurrencyRates();

        return $this->render('@ExchangeRate/Default/index.html.twig', [
            'currencyRates' => $currencyRates,
        ]);
    }
}
