<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Acme\ProductOption;
use App\Acme\ProductOptionType;
use PHPUnit\Framework\TestCase;

class ProductOptionTest extends TestCase
{
    /** @test */
    public function annual_product_option_is_created(): void
    {
        $optionTitle = 'Basic: 6GB Data - 1 Year';
        $description = 'The basic starter subscription.';
        $price = 199.99;
        $discount = 5.86;

        $productOption = ProductOption::createAnnualOption(
            $optionTitle,
            $description,
            $price,
            $discount
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

        $productOption = ProductOption::createMonthlyOption(
            $optionTitle,
            $description,
            $price,
        );

        $this->assertInstanceOf(ProductOption::class, $productOption);
        $this->assertEquals($optionTitle, $productOption->optionTitle);
        $this->assertEquals($description, $productOption->description);
        $this->assertEquals($price, $productOption->monthlyPrice);
        $this->assertEquals($price * 12, $productOption->annualPrice);
        $this->assertEquals(0, $productOption->discount);
        $this->assertEquals(ProductOptionType::MONTHLY_OPTION, $productOption->productOptionType);
    }

    /** @test */
    public function product_option_is_transformed(): void
    {
        $optionTitle = 'Basic: 500GB Data - 12 Month';
        $description = 'The basic starter subscription.';
        $price = 5.99;

        $productOption = ProductOption::createMonthlyOption(
            $optionTitle,
            $description,
            $price,
        )->transform();

        $this->assertIsArray($productOption);
        $this->assertEquals($optionTitle, $productOption['option title']);
        $this->assertEquals($description, $productOption['description']);
        $this->assertEquals('£5.99', $productOption['price']);
        $this->assertEquals('£5.99', $productOption['monthly price']);
        $this->assertEquals('£71.88', $productOption['annual price']);
        $this->assertEquals("£0.00", $productOption['discount']);
        $this->assertEquals("MONTHLY_OPTION", $productOption['type']);
    }
}
