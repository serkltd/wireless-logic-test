<?php

declare(strict_types=1);

namespace App\Acme;

use NumberFormatter;

final readonly class ProductOption implements Item
{
    private const LOCALE = 'en_GB';

    public function __construct(
        public string $optionTitle,
        public string $description,
        public float $monthlyPrice,
        public float $annualPrice,
        public float $discount,
        public ProductOptionType $productOptionType
    ) {
    }

    public static function createAnnualOption(
        string $optionTitle,
        string $description,
        float $price,
        float $discount,
    ): self {
        return new self(
            $optionTitle,
            $description,
            (float) number_format(fdiv($price, 12), 2),
            $price,
            $discount,
            ProductOptionType::ANNUAL_OPTION
        );
    }

    public static function createMonthlyOption(
        string $optionTitle,
        string $description,
        float $price,
    ): self {
        return new self(
            $optionTitle,
            $description,
            $price,
            $price * 12,
            0,
            ProductOptionType::MONTHLY_OPTION
        );
    }

    /** {@inheritdoc} */
    public function transform(): array
    {
        $formatter = new NumberFormatter(self::LOCALE, NumberFormatter::CURRENCY);

        return [
            'option title' => $this->optionTitle,
            'description' => $this->description,
            'price' => $this->productOptionType === ProductOptionType::MONTHLY_OPTION
                ? $formatter->format($this->monthlyPrice)
                : $formatter->format($this->annualPrice),
            'monthly price' => $formatter->format($this->monthlyPrice),
            'annual price' => $formatter->format($this->annualPrice),
            'discount' => $formatter->format($this->discount),
            'type' => $this->productOptionType->name,
        ];
    }
}
