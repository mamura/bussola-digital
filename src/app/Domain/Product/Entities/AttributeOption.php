<?php

namespace App\Domain\Product\Entities;

class AttributeOptions
{
    public function __construct(
        public readonly int $id,
        public string $value
    ) {}
}
