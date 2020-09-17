<?php

namespace App\Tests\Unit\Domain\Distributor\Entity\ValueObject;

use App\Domain\Distributor\Entity\Distributor;
use App\Domain\Distributor\Entity\ValueObject\ExistData;
use App\Domain\Pharmacy\Entity\Pharmacy;
use App\Domain\Preparation\Entity\Preparation;
use App\Domain\Preparation\Entity\PreparationUndefined;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class ExistDataTest extends TestCase
{
    /** @var array */
    private $preparations;
    /** @var array */
    private $preparationsUndefined;
    /** @var array */
    private $pharmacies;

    protected function setUp(): void
    {
        parent::setUp();

        $this->preparations = $this->createPreparations($this->createDistributor(), $this->createPharmacy());
        $this->preparationsUndefined = $this->createPreparationsUndefined();
        $this->pharmacies = $this->createPharmacies();
    }

    protected function tearDown(): void
    {
        unset(
            $this->preparations,
            $this->preparationsUndefined,
            $this->pharmacies
        );

        parent::tearDown();
    }

    public function testSuccessfulCreationExistData(): void
    {
        $existData = new ExistData(
            $this->preparations,
            $this->preparationsUndefined,
            $this->pharmacies
        );

        $this->assertCount(2, $existData->getPreparations());
        $this->assertInstanceOf(Preparation::class, $existData->getPreparations()[0]);

        $this->assertCount(3, $existData->getPreparationsUndefined());
        $this->assertInstanceOf(PreparationUndefined::class, $existData->getPreparationsUndefined()[0]);

        $this->assertCount(3, $existData->getPharmacies());
        $this->assertInstanceOf(Pharmacy::class, $existData->getPharmacies()[0]);
    }

    public function testSuccessfulMergeData(): void
    {
        $existData = new ExistData(
            $this->preparations,
            $this->preparationsUndefined,
            $this->pharmacies
        );

        $this->assertCount(8, $existData->merge());
    }

    public function testSuccessfulInCollectionExistAllData(): void
    {
        $existData = new ExistData(
            $this->preparations,
            $this->preparationsUndefined,
            $this->pharmacies
        );

        $collectionWithAllData = $existData->getCollectionWithAllData();

        $this->assertInstanceOf(ArrayCollection::class, $collectionWithAllData);
        $this->assertNotEmpty($collectionWithAllData);
        $this->assertCount(8, $collectionWithAllData);
    }

    private function createPreparations(Distributor $distributor, Pharmacy $pharmacy): array
    {
        $preparations = [];

        for ($i = 1; $i < 3; $i++) {
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

    private function createPreparationsUndefined(): array
    {
        $preparationsUndefined = [];

        for ($i = 0; $i < 3; $i++) {
            $preparationsUndefined[] = new PreparationUndefined(
                $id = Uuid::uuid4(),
                $name = 'Номенклатура Аптечная точка Отгружено'
            );
        }

        return $preparationsUndefined;
    }

    private function createPharmacies(): array
    {
        $pharmacies = [];

        for ($i = 0; $i < 3; $i++) {
            $pharmacies[] = new Pharmacy(
                Uuid::uuid4(),
                sprintf('ул Зорге %s', $i)
            );
        }

        return $pharmacies;
    }

    private function createPharmacy(): Pharmacy
    {
        return new Pharmacy(Uuid::uuid4(), 'ул Зорге 3');
    }

    private function createDistributor(): Distributor
    {
        return new Distributor( Uuid::uuid4(), 'Дистрибьютер 1');
    }
}
