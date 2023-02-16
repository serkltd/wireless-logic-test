<?php

declare(strict_types=1);

namespace App\Service\Acme;

use App\Acme\ProductOption;
use App\Service\NodeService;
use App\Service\ParserService;
use Symfony\Component\DomCrawler\Crawler;

class AcmeParserService implements ParserService
{
    private NodeService $acmeNodeService;

    public function __construct(AcmeNodeService $acmeNodeService)
    {
        $this->acmeNodeService = $acmeNodeService;
    }

    /** {@inheritdoc} */
    public function parse(Crawler $crawler): array
    {
        $service = $this->acmeNodeService;
        return $crawler
            ->filter('#subscriptions .package')
            ->each(function (Crawler $node) use ($service) {
                return $service->getItemFromNode($node);
            });
    }

    /** {@inheritDoc} */
    public function sort(array $productOptions): array
    {
        usort($productOptions, fn(ProductOption $a, ProductOption $b) => ($b->annualPrice <=> $a->annualPrice));
        return $productOptions;
    }

    /** {@inheritDoc} */
    public function transform(array $productOptions): array
    {
        return array_map(function ($productOption) {
            return $productOption->transform();
        }, $productOptions);
    }
}
