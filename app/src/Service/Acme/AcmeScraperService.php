<?php

declare(strict_types=1);

namespace App\Service\Acme;

use App\Service\ParserService;
use App\Service\ScraperService;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class AcmeScraperService implements ScraperService
{
    private ParserService $parserService;

    private HttpClientInterface $client;

    private string $url;


    /** @throws HttpException */
    public function __construct(string $url, AcmeParserService $parserService, HttpClientInterface $httpClient)
    {
        $this->validateURL($url);
        $this->url = $url;
        $this->parserService = $parserService;
        $this->client = $httpClient;
    }

    /** {@inheritdoc} */
    public function scrape(): array
    {
        $crawler = $this->getCrawler();
        $productOptions = $this->parserService->parse($crawler);
        $sortedOptions = $this->parserService->sort($productOptions);
        return $this->parserService->transform($sortedOptions);
    }

    private function getCrawler(): Crawler
    {
        return new Crawler($this->getHtml($this->url));
    }

    private function getHtml(string $url): string
    {
        return $this->client->request('GET', $url)->getContent();
    }

    /** @throws HttpException */
    private function validateURL(string $url): void
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new HttpException(400, sprintf('URL %s is not a valid URL', $url));
        }
    }

    /** {@inheritdoc} */
    public function getJson(array $items): string
    {
        return json_encode($items, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR, 512);
    }
}
