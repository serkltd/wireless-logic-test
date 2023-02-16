<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Acme\ProductOption;
use App\Acme\ProductOptionFactory;
use App\Acme\ProductOptionType;
use PHPUnit\Framework\TestCase;

class ProductOptionFactoryTest extends TestCase
{
    /** @test */
    public function annual_product_option_is_created(): void
    {
        $optionTitle = 'Basic: 6GB Data - 1 Year';
        $description = 'The basic starter subscription.';
        $price = 199.99;
        $discount = 5.86;

        $productOptionFactory = new ProductOptionFactory();
        $productOption = $productOptionFactory->create(
            $optionTitle,
            $description,
            $price,
            $discount,
            ProductOptionType::ANNUAL_OPTION
        );

        $this->assertInstanceOf(ProductOption::class, $productOption);
        $this->assertEquals($optionTitle, $productOption->optionTitle);
        $this->assertEquals($description, $productOption->description);
        $this->assertEquals(16.67, $productOption->monthlyPrice);
        $this->assertEquals($price, $productOption->annualPrice);
        $this->assertEquals($discount, $productOption->discount);
        $this->assertEquals(ProductOptionType::ANNUAL_OPTION, $productOption->productOptionType);
    }

    /** @test */
    public function monthly_product_option_is_created(): void
    {
        $optionTitle = 'Basic: 500MB Data - 12 Month';
        $description = 'The basic starter subscription.';
        $price = 5.99;
        $discount = 0;

        $productOptionFactory = new ProductOptionFactory();
        $productOption = $productOptionFactory->create(
            $optionTitle,
            'The basic starter subscription.',
            5.99,
            $discount,
            ProductOptionType::MONTHLY_OPTION
        );

        $this->assertInstanceOf(ProductOption::class, $productOption);
        $this->assertEquals($optionTitle, $productOption->optionTitle);
        $this->assertEquals($description, $productOption->description);
        $this->assertEquals($price, $productOption->monthlyPrice);
        $this->assertEquals($price * 12, $productOption->annualPrice);
        $this->assertEquals($discount, $productOption->discount);
        $this->assertEquals(ProductOptionType::MONTHLY_OPTION, $productOption->productOptionType);
    }
}
