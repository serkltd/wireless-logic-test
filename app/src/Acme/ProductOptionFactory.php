<?php

declare(strict_types=1);

namespace App\Acme;

class ProductOptionFactory implements ProductOptionFactoryInterface
{
    public function create(
        string $optionTitle,
        string $description,
        float $price,
        float $discount,
        ProductOptionType $productOptionType
    ): ProductOption {
        return ($productOptionType === ProductOptionType::ANNUAL_OPTION)
            ? ProductOption::createAnnualOption(
                $optionTitle,
                $description,
                $price,
                $discount,
            ) : ProductOption::createMonthlyOption(
                $optionTitle,
                $description,
                $price,
            );
    }
}
