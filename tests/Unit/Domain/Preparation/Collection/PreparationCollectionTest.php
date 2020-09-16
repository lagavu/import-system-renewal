<?php

namespace App\Tests\Unit\Domain\Preparation\Collection;

use App\Domain\Preparation\Collection\PreparationCollection;
use App\Domain\Preparation\Entity\Preparation;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class PreparationCollectionTest extends TestCase
{
    /** @var PreparationCollection */
    private $preparationCollection;
    /** @var ArrayCollection */
    private $collection;

    protected function setUp(): void
    {
        parent::setUp();

        $this->preparationCollection = new PreparationCollection();
        $this->collection = new ArrayCollection();
        $this->collection->add($this->createPreparation('Препарат 5'));
        $this->collection->add($this->createPreparation('Препарат 2'));
    }

    protected function tearDown(): void
    {
        unset(
            $this->preparationCollection,
            $this->collection
        );

        parent::tearDown();
    }

    public function testPreparationNameNotExistsWhenCollectionEmpty(): void
    {
        $preparationName = 'Препарат 12';

        $this->assertFalse($this->preparationCollection->exist($this->collection, $preparationName));
    }

    public function testPreparationNameNotExistsWhenCollectionNotContainName(): void
    {
        $preparationName = 'Препарат 12';

        $this->collection->add($this->createPreparation('Препарат 9'));

        $this->assertFalse($this->preparationCollection->exist($this->collection, $preparationName));
    }

    public function testPreparationNameExists(): void
    {
        $preparationName = 'Препарат 12';

        $this->collection->add($this->createPreparation('Препарат 12'));

        $this->assertTrue($this->preparationCollection->exist($this->collection, $preparationName));
    }

    public function testGetByValuePreparation(): void
    {
        $preparationName = 'Препарат 12';

        $expectedPreparation = $this->createPreparation($preparationName);
        $this->collection->add($expectedPreparation);

        $this->assertEquals($expectedPreparation, $this->preparationCollection->getByValue($this->collection, $preparationName));
    }

    private function createPreparation(string $name): Preparation
    {
        $stub = $this->createMock(Preparation::class);
        $stub
            ->method('getName')
            ->willReturn($name);

        return $stub;
    }
}
