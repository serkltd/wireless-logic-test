<?php

declare(strict_types=1);

namespace app\tests\Service\ParserService;

use App\Acme\ProductOption;
use App\Acme\ProductOptionFactory;
use App\Service\Acme\AcmeNodeService;
use App\Service\Acme\AcmeParserService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class AcmeParserServiceTest extends KernelTestCase
{
    private AcmeParserService $acmeParserService;

    public function setUp(): void
    {
        self::bootKernel();

        $productOptionFactory = new ProductOptionFactory();
        $acmeNodeService = new AcmeNodeService($productOptionFactory);
        $this->acmeParserService = new AcmeParserService($acmeNodeService);
    }

    /** @test */
    public function product_options_are_parsed(): void
    {
        $crawler = new Crawler($this->getPage());

        $actual = $this->acmeParserService->parse($crawler);

        $this->assertIsArray($actual);
        $this->assertEquals($this->parsedData(), $actual);
    }

    /** @test */
    public function product_options_are_sorted_by_annual_price_descending(): void
    {
        $crawler = new Crawler($this->getPage());

        $productOptions = $this->acmeParserService->parse($crawler);
        $actual = $this->acmeParserService->sort($productOptions);

        $this->assertIsArray($actual);
        $this->assertEquals($this->sortedData(), $actual);
    }

    /** @test */
    public function product_options_are_transformed(): void
    {
        $crawler = new Crawler($this->getPage());

        $productOptions = $this->acmeParserService->parse($crawler);
        $actual = $this->acmeParserService->transform($productOptions);

        $this->assertIsArray($actual);
        $this->assertEquals($this->transformedData(), $actual);
    }


    private function getPage(): string
    {
        $contents = file_get_contents(__DIR__ . '../../../fixtures/wltest.html');
        if (!$contents) {
            throw new FileException('Unable to get file contents.');
        }
        return $contents;
    }

    /**
     * @return array<ProductOption>
     */
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

    /** @return array<ProductOption> */
    private function sortedData(): array
    {
        return [
            ProductOption::createMonthlyOption(
                'Optimum: 2 GB Data - 12 Months',
                'The optimum subscription providing you with enough service time to support the above-average user to enable your device to be up and running with inclusive Data and SMS services',
                15.99
            ),
            ProductOption::createAnnualOption(
                'Optimum: 24GB Data - 1 Year',
                'The optimum subscription providing you with enough service time to support the above-average with data and SMS services to allow access to your device.',
                174.00,
                17.90
            ),
            ProductOption::createMonthlyOption(
                'Standard: 1GB Data - 12 Months',
                'The standard subscription providing you with enough service time to support the average user to enable your device to be up and running with inclusive Data and SMS services.',
                9.99
            ),
            ProductOption::createAnnualOption(
                'Standard: 12GB Data - 1 Year',
                'The standard subscription providing you with enough service time to support the average user with Data and SMS services to allow access to your device.',
                108.00,
                11.90
            ),
            ProductOption::createMonthlyOption(
                'Basic: 500MB Data - 12 Months',
                'The basic starter subscription providing you with all you need to get your device up and running with inclusive Data and SMS services.',
                5.99
            ),
            ProductOption::createAnnualOption(
                'Basic: 6GB Data - 1 Year',
                'The basic starter subscription providing you with all you need to get you up and running with Data and SMS services to allow access to your device.',
                66.00,
                5.86
            ),
        ];
    }

    /** @return array<array<string, string|false>> */
    private function transformedData(): array
    {
        return [
            [
                'option title' => 'Basic: 500MB Data - 12 Months',
                'description' => 'The basic starter subscription providing you with all you need to get your device up and running with inclusive Data and SMS services.',
                'price' => '£5.99',
                'monthly price' => '£5.99',
                'annual price' => '£71.88',
                'discount' => '£0.00',
                'type' => 'MONTHLY_OPTION'
            ],
            [
                'option title' => 'Standard: 1GB Data - 12 Months',
                'description' => 'The standard subscription providing you with enough service time to support the average user to enable your device to be up and running with inclusive Data and SMS services.',
                'price' => '£9.99',
                'monthly price' => '£9.99',
                'annual price' => '£119.88',
                'discount' => '£0.00',
                'type' => 'MONTHLY_OPTION'
            ],
            [
                'option title' => 'Optimum: 2 GB Data - 12 Months',
                'description' => 'The optimum subscription providing you with enough service time to support the above-average user to enable your device to be up and running with inclusive Data and SMS services',
                'price' => '£15.99',
                'monthly price' => '£15.99',
                'annual price' => '£191.88',
                'discount' => '£0.00',
                'type' => 'MONTHLY_OPTION'
            ],
            [
                'option title' => 'Basic: 6GB Data - 1 Year',
                'description' => 'The basic starter subscription providing you with all you need to get you up and running with Data and SMS services to allow access to your device.',
                'price' => '£66.00',
                'monthly price' => '£5.50',
                'annual price' => '£66.00',
                'discount' => '£5.86',
                'type' => 'ANNUAL_OPTION'
            ],
            [
                'option title' => 'Standard: 12GB Data - 1 Year',
                'description' => 'The standard subscription providing you with enough service time to support the average user with Data and SMS services to allow access to your device.',
                'monthly price' => '£9.00',
                'price' => '£108.00',
                'annual price' => '£108.00',
                'discount' => '£11.90',
                'type' => 'ANNUAL_OPTION'
            ],
            [
                'option title' => 'Optimum: 24GB Data - 1 Year',
                'description' => 'The optimum subscription providing you with enough service time to support the above-average with data and SMS services to allow access to your device.',
                'price' => '£174.00',
                'monthly price' => '£14.50',
                'annual price' => '£174.00',
                'discount' => '£17.90',
                'type' => 'ANNUAL_OPTION'
            ],
        ];
    }
}
