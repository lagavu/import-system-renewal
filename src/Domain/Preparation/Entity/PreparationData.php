<?php

namespace App\Domain\Preparation\Entity;

class PreparationData
{
    private $name;
    private $address;
    private $quantity;

    public function __construct(string $name, string $address, int $quantity)
    {
        $this->name = $name;
        $this->address = $address;
        $this->quantity = $quantity;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }
}
