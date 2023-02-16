<?php

declare(strict_types=1);

namespace app\tests\Service;

use App\Acme\ProductOption;
use App\Acme\ProductOptionFactory;
use App\Service\Acme\AcmeNodeService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class AcmeNodeServiceTest extends KernelTestCase
{
    /** @test */
    public function it_gets_the_product_options(): void
    {
        $crawler = new Crawler($this->getPage());

        $acmeNodeService = new AcmeNodeService(new ProductOptionFactory());

        $expected = $this->parsedData();

        $crawler
            ->filter('#subscriptions .package')
            ->each(function (Crawler $node, $i) use ($acmeNodeService, $expected) {
                $actual = $acmeNodeService->getItemFromNode($node);
                $this->assertInstanceOf(ProductOption::class, $actual);
                $this->assertEquals($expected[$i], $actual);
            });
    }

    private function getPage(): string
    {
        $contents = file_get_contents(__DIR__ . '../../../fixtures/wltest.html');
        if (!$contents) {
            throw new FileException('Unable to get file contents.');
        }
        return $contents;
    }

    /** @return array<ProductOption> */
    private function parsedData(): array
    {
        return [
            ProductOption::createMonthlyOption(
                'Basic: 500MB Data - 12 Months',
                'The basic starter subscription providing you with all you need to get your device up and running with inclusive Data and SMS services.',
                5.99
            ),
            ProductOption::createMonthlyOption(
                'Standard: 1GB Data - 12 Months',
                'The standard subscription providing you with enough service time to support the average user to enable your device to be up and running with inclusive Data and SMS services.',
                9.99
            ),
            ProductOption::createMonthlyOption(
                'Optimum: 2 GB Data - 12 Months',
                'The optimum subscription providing you with enough service time to support the above-average user to enable your device to be up and running with inclusive Data and SMS services',
                15.99
            ),
            ProductOption::createAnnualOption(
                'Basic: 6GB Data - 1 Year',
                'The basic starter subscription providing you with all you need to get you up and running with Data and SMS services to allow access to your device.',
                66.00,
                5.86
            ),
            ProductOption::createAnnualOption(
                'Standard: 12GB Data - 1 Year',
                'The standard subscription providing you with enough service time to support the average user with Data and SMS services to allow access to your device.',
                108.00,
                11.90
            ),
            ProductOption::createAnnualOption(
                'Optimum: 24GB Data - 1 Year',
                'The optimum subscription providing you with enough service time to support the above-average with data and SMS services to allow access to your device.',
                174.00,
                17.90
            ),
        ];
    }
}
