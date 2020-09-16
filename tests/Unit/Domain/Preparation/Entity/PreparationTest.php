<?php

namespace App\Tests\Unit\Domain\Preparation\Entity;

use App\Domain\Distributor\Entity\Distributor;
use App\Domain\Pharmacy\Entity\Pharmacy;
use App\Domain\Preparation\Entity\Preparation;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class PreparationTest extends TestCase
{
    /** @var Pharmacy */
    private $pharmacy;
    /** @var Distributor */
    private $distributor;

    protected function setUp(): void
    {
        parent::setUp();

        $this->pharmacy = $this->createMock(Pharmacy::class);
        $this->distributor = $this->createMock(Distributor::class);
    }

    protected function tearDown(): void
    {
        unset(
            $this->pharmacy,
            $this->distributor
        );

        parent::tearDown();
    }

    public function testSuccessfulCreationPreparation(): void
    {
        $preparation = $this->createPreparation(
            $id = Uuid::uuid4(),
            $name = 'Препарат 1',
            $quantity = 20
        );

        $this->assertEquals($id, $preparation->getId());
        $this->assertEquals($name, $preparation->getName());
        $this->assertEquals($quantity, $preparation->getQuantity());
        $this->assertEquals($this->pharmacy, $preparation->getPharmacy());
        $this->assertEquals($this->distributor, $preparation->getDistributor());
    }

    public function testSuccessfulIncreaseQuantity(): void
    {
        $preparation = $this->createPreparation(
            $id = Uuid::uuid4(),
            $name = 'Препарат 1',
            $quantity = 20
        );

        $increasedQuantity = 40;

        $this->assertEquals($increasedQuantity, $preparation->addQuantity(20)->getQuantity());
    }

    private function createPreparation(UuidInterface $id, string $name, int $quantity): Preparation
    {
        return new Preparation(
            $id,
            $name,
            $quantity,
            $this->pharmacy,
            $this->distributor
        );
    }
}
