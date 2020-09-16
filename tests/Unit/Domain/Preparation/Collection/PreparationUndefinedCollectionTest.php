<?php

namespace App\Tests\Unit\Domain\Preparation\Collection;

use App\Domain\Preparation\Collection\PreparationUndefinedCollection;
use App\Domain\Preparation\Entity\PreparationUndefined;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class PreparationUndefinedCollectionTest extends TestCase
{
    /** @var PreparationUndefinedCollection */
    private $preparationUndefinedCollection;

    /** @var ArrayCollection */
    private $collection;

    protected function setUp(): void
    {
        parent::setUp();

        $this->preparationUndefinedCollection = new PreparationUndefinedCollection();
        $this->collection = new ArrayCollection();
        $this->collection->add($this->createPreparationUndefined('Аптечная точка Отгружено'));
        $this->collection->add($this->createPreparationUndefined('Аптека Препарат Количество'));
    }

    protected function tearDown(): void
    {
        unset(
            $this->preparationUndefinedCollection,
            $this->collection
        );

        parent::tearDown();
    }

    public function testPreparationUndefinedNameNotExistWhenCollectionEmpty(): void
    {
        $collection = new ArrayCollection();
        $preparationUndefinedName = 'Аптечная точка Отгружено';

        $this->assertFalse($this->preparationUndefinedCollection->exist($collection, $preparationUndefinedName));
    }

    public function testPreparationUndefinedNameNotExistsWhenCollectionNotContainName(): void
    {
        $preparationUndefinedName = 'Улица Препарат Количество ';

        $this->assertFalse($this->preparationUndefinedCollection->exist($this->collection, $preparationUndefinedName));
    }

    public function testPreparationUndefinedNameExists(): void
    {
        $preparationUndefinedName = 'Аптека Препарат Количество';

        $this->assertTrue($this->preparationUndefinedCollection->exist($this->collection, $preparationUndefinedName));
    }

    private function createPreparationUndefined(string $name): PreparationUndefined
    {
        $stub = $this->createMock(PreparationUndefined::class);
        $stub
            ->method('getName')
            ->willReturn($name);

        return $stub;
    }
}
