<?php

namespace App\Tests\Unit\Domain\Preparation\Entity;

use App\Domain\Preparation\Entity\PreparationData;
use PHPUnit\Framework\TestCase;

class PreparationDataTest extends TestCase
{
    public function testSuccessfulCreationPreparationUndefined(): void
    {
        $preparationData = new PreparationData(
            $name = 'Препарат 12',
            $address = 'ул Зорге 3',
            $quantity = 20
        );

        $this->assertEquals($name, $preparationData->getName());
        $this->assertEquals($address, $preparationData->getAddress());
        $this->assertEquals($quantity, $preparationData->getQuantity());
    }
}
