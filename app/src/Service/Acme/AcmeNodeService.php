<?php

declare(strict_types=1);

namespace App\Service\Acme;

use App\Acme\ProductOption;
use App\Acme\ProductOptionFactory;
use App\Acme\ProductOptionFactoryInterface;
use App\Acme\ProductOptionType;
use App\Service\NodeService;
use Symfony\Component\DomCrawler\Crawler;

class AcmeNodeService implements NodeService
{
    private const SELECTOR_OPTION_TITLE = 'h3';
    private const SELECTOR_DESCRIPTION = '.package-name';
    private const SELECTOR_PRICE = 'div.package-price span.price-big';
    private const SELECTOR_DISCOUNT = 'p';

    private ProductOptionFactoryInterface $productOptionFactory;

    public function __construct(ProductOptionFactory $productOptionFactory)
    {
        $this->productOptionFactory = $productOptionFactory;
    }

    public function getItemFromNode(Crawler $node): ProductOption
    {
        $optionTitle = $this->getOptionTitle($node);
        $description = $this->getDescription($node);
        $price = $this->getPrice($node);
        $discount = $this->getDiscount($node);

        return $this->productOptionFactory->create(
            $optionTitle,
            $description,
            $price,
            $discount,
            $this->isMonthly($optionTitle)
                ? ProductOptionType::MONTHLY_OPTION
                : ProductOptionType::ANNUAL_OPTION
        );
    }

    private function getOptionTitle(Crawler $node): string
    {
        return $node->filter(self::SELECTOR_OPTION_TITLE)->first()->html();
    }

    private function getDescription(Crawler $node): string
    {
        return $node->filter(self::SELECTOR_DESCRIPTION)->first()->html();
    }

    private function getPrice(Crawler $node): float
    {
        return $this->extractMoneyFromText(
            $node->filter(self::SELECTOR_PRICE)->text()
        );
    }

    private function getDiscount(Crawler $node): float
    {
        $discount = $node->filter(self::SELECTOR_DISCOUNT)->each(function (Crawler $node) {
            return $this->extractMoneyFromText($node->text());
        });

        return !($discount) ? 0 : $discount[0];
    }

    private function isMonthly(string $optionTitle): bool
    {
        return str_contains($optionTitle, ProductOptionType::MONTHLY_OPTION->value);
    }

    private function extractMoneyFromText(string $moneyString): float
    {
        preg_match('/\d+\.?\d*/', $moneyString, $matches);
        return (float)$matches[0];
    }
}
