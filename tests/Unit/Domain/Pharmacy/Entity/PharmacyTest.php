<?php

namespace App\Tests\Unit\Domain\Pharmacy\Entity;

use App\Domain\Distributor\Entity\Distributor;
use App\Domain\Pharmacy\Entity\Pharmacy;
use App\Domain\Preparation\Entity\Preparation;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class PharmacyTest extends TestCase
{
    public function testSuccessfulCreationPharmacy(): void
    {
        $pharmacy = new Pharmacy(
            $id = Uuid::uuid4(),
            $address = 'ул Зорге 3'
        );

        $this->assertEquals($id, $pharmacy->getId());
        $this->assertEquals($address, $pharmacy->getAddress());
    }

    public function testSuccessfulReceiptOfTotalNumberOfPreparationsInPharmacy(): void
    {
        $pharmacy = new Pharmacy(Uuid::uuid4(), 'ул Зорге 3');
        $preparations = $this->createPreparations($pharmacy);

        $expectedCommonQuantity = 60;

        $this->assertEquals($expectedCommonQuantity, $pharmacy->getCommonQuantity($preparations));
    }

    private function createPreparations(Pharmacy $pharmacy): array
    {
        $distributor = $this->createMock(Distributor::class);

        $preparations = [];

        for ($i = 0; $i < 3; $i++) {
            $preparations[] = new Preparation(
                Uuid::uuid4(),
                sprintf('Препарат %s', $i),
                20,
                $pharmacy,
                $distributor
            );
        }

        return $preparations;
    }
}
