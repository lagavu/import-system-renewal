<?php

namespace App\Tests\Unit\Domain\Distributor\Factory\FileDistributorFactory\FileDistributorOne;

use App\Domain\Distributor\Entity\Distributor;
use App\Domain\Distributor\Entity\ValueObject\ExistData;
use App\Domain\Distributor\Factory\FileDistributorFactory\FileDistributorFactory;
use App\Domain\Pharmacy\Entity\Pharmacy;
use App\Domain\Preparation\Entity\Preparation;
use App\Domain\Preparation\Entity\PreparationUndefined;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\File\File;

class FileDistributorOneTest extends TestCase
{
    private const IMPORT_FILE_WITH_PREPARATIONS_PATH = '/DataFixtures/ImportFileForDistributor/distributor1.txt';

    public function testSuccessfulDataProcessingForDistributorOne(): void
    {
        $distributor = new Distributor( Uuid::uuid4(), 'Дистрибьютер 1');
        $pharmacy = new Pharmacy(Uuid::uuid4(), 'ул Зорге 3');

        $preparations = $this->createPreparations($distributor, $pharmacy);
        $preparationsUndefined = $this->createPreparationsUndefined();
        $pharmacies = $this->createPharmacies();
        $existData = new ExistData($preparations, $preparationsUndefined, $pharmacies);

        $pathToFolderTests = dirname(__DIR__, 6);
        $file = new File($pathToFolderTests.self::IMPORT_FILE_WITH_PREPARATIONS_PATH);

        $fileDistributorFactory = new FileDistributorFactory();
        $fileDistributorFactory = $fileDistributorFactory->get($distributor->getName());
        $preparedData = $fileDistributorFactory
            ->buildFileDistributor()
            ->process($distributor, $file, $existData);

        $this->assertNotEmpty($preparedData);
        $this->assertCount(12, $preparedData);

        /** @var Preparation $preparedPreparation */
        $preparedPreparation = $preparations[0];

        $this->assertInstanceOf(Preparation::class, $preparedPreparation);
        $this->assertTrue($preparedData->contains($preparedPreparation));
        $this->assertContains('Препарат ', $preparedPreparation->getName());
        $this->assertEquals($pharmacy, $preparedPreparation->getPharmacy());
        $this->assertEquals($distributor, $preparedPreparation->getDistributor());

        /** @var PreparationUndefined $preparationUndefined */
        $preparationUndefined = $preparationsUndefined[0];

        $this->assertInstanceOf(PreparationUndefined::class, $preparationUndefined);
        $this->assertTrue($preparedData->contains($preparationUndefined));
        $this->assertContains('Номенклатура Аптечная точка Отгружено', $preparationUndefined->getName());

        /** @var Pharmacy $preparedPharmacy */
        $preparedPharmacy = $pharmacies[0];

        $this->assertInstanceOf(Pharmacy::class, $preparedPharmacy);
        $this->assertTrue($preparedData->contains($preparedPharmacy));
        $this->assertContains('ул Зорге ', $preparedPharmacy->getAddress());
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
}
