<?php

declare(strict_types=1);

namespace App\Acme;

interface Item
{
    /** @return array<string, string|false> */
    public function transform(): array;
}
