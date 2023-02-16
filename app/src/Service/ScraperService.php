<?php

declare(strict_types=1);

namespace App\Service;

interface ScraperService
{
    /** @return array<array<string, string|false>> */
    public function scrape(): array;

    /** @param array<array<string, string|false>> $items */
    public function getJson(array $items): string;
}
