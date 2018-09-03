<?php

namespace ExchangeRateBundle\Command;

use Symfony\Component\Console\Helper\Table;
use ExchangeRateBundle\Services\ExchangeRates;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use ExchangeRateBundle\Services\BetterPriceFilterer;
use Symfony\Component\Console\Output\OutputInterface;
use ExchangeRateBundle\Services\ExchangeRateRegistrar;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

class ExchangeFetchRatesCommand extends ContainerAwareCommand
{
    /**
     * Configures the current command.
     *
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('exchange:fetch-rates')
            ->setDescription('Fetch currency rates from providers.');
    }

    /**
     * Executes the current command.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     * @throws \ExchangeRateBundle\Exceptions\CurrencyNotFound
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Fetching Currency Rates...');

        $rates = $this->getExchangeRatesInstance()->fetchCurrencyRates();

        $betterPrices = (new BetterPriceFilterer($rates))->filter();

        $this->getExchangeRateRegistrarInstance()->register($betterPrices);

        $table = new Table($output);
        $table->setHeaders(array('Parity', 'Amount', 'Provider'));
        $rows = [];
        foreach ($rates as $key => $parities) {
            foreach ($parities as $rate) {
                array_push($rows, ['parity' => $key, 'amount' => $rate['amount'], 'provider' => $rate['provider']]);
            }

            array_push($rows, new TableSeparator());
        }
        array_pop($rows);
        $table->setRows($rows);
        $table->render();

        $output->writeln('...');

        $rows = [];
        foreach ($betterPrices as $key => $rate) {
            array_push($rows, ['parity' => $key, 'amount' => $rate['amount'], 'provider' => $rate['provider']]);
        }

        $table->setRows($rows);
        $table->render();

        $output->writeln('Currency Rates Fetched.');
    }

    /**
     * Returns the exchange rate service class instance.
     *
     * @return ExchangeRates
     */
    private function getExchangeRatesInstance()
    {
        return $container = $this->getContainer()->get('exchange_rate_service');
    }

    /**
     * Returns the exhcange rate registrar service instance.
     *
     * @return ExchangeRateRegistrar
     */
    private function getExchangeRateRegistrarInstance()
    {
        return $container = $this->getContainer()->get('exchange_rate_registrar_service');
    }

}
