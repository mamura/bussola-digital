<?php

namespace App\Domain\Product\Entities;

class Attribute
{
    public function __construct(
        public readonly int $id,
        public string $name,
        public string $inputType,
        public array $options = []
    ) {}
}
