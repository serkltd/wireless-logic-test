<?php

declare(strict_types=1);

namespace App\Service;

use App\Acme\ProductOption;
use Symfony\Component\DomCrawler\Crawler;

interface ParserService
{
    /** @return array<ProductOption> */
    public function parse(Crawler $crawler): array;

    /**
     * @param array<ProductOption> $productOptions
     * @return array<ProductOption>
     */
    public function sort(array $productOptions): array;

    /**
     * @param array<ProductOption> $productOptions
     * @return array<array<string, string|false>>
     */
    public function transform(array $productOptions): array;
}
