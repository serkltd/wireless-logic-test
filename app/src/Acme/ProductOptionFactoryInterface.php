<?php

declare(strict_types=1);

namespace App\Acme;

interface ProductOptionFactoryInterface
{
    public function create(
        string $optionTitle,
        string $description,
        float $price,
        float $discount,
        ProductOptionType $productOptionType
    ): ProductOption;
}
