<?php

declare(strict_types=1);

namespace App\Command;

use App\Service\Acme\AcmeScraperService;
use App\Service\ScraperService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:scrape:acme',
    description: 'Scrape https://wltest.dns-systems.net/ and return all product options.',
)]
class ScrapeAcmeCommand extends Command
{
    private ScraperService $acmeScraperService;

    public function __construct(
        AcmeScraperService $acmeScraperService,
    ) {
        $this->acmeScraperService = $acmeScraperService;

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $productOptions = $this->acmeScraperService->scrape();

            $io->writeln(
                $this->acmeScraperService->getJson($productOptions)
            );
        } catch (\Throwable $exception) {
            $io->error(['Unable to scrape webpage.', $exception->getMessage()]);
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
