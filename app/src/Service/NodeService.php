<?php

declare(strict_types=1);

namespace App\Service;

use App\Acme\ProductOption;
use Symfony\Component\DomCrawler\Crawler;

interface NodeService
{
    public function getItemFromNode(Crawler $crawler): ProductOption;
}
