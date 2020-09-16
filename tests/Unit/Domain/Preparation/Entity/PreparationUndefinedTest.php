<?php

namespace App\Tests\Unit\Domain\Preparation\Entity;

use App\Domain\Preparation\Entity\PreparationUndefined;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class PreparationUndefinedTest extends TestCase
{
    public function testSuccessfulCreationPreparationUndefined(): void
    {
        $preparationUndefined = new PreparationUndefined(
            $id = Uuid::uuid4(),
            $name = 'Номенклатура Аптечная точка Отгружено'
        );

        $this->assertEquals($id, $preparationUndefined->getId());
        $this->assertEquals($name, $preparationUndefined->getName());
    }
}
