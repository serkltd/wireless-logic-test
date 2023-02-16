<?php

declare(strict_types=1);

namespace App\Acme;

enum ProductOptionType: string
{
    case ANNUAL_OPTION = 'Year';
    case MONTHLY_OPTION = 'Months';
}
