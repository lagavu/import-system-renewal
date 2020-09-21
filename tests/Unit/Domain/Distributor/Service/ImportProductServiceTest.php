<?php

namespace App\Tests\Unit\Domain\Distributor\Service;

use App\Domain\Distributor\Exception\IncorrectImportProductException;
use App\Domain\Distributor\Service\ImportProductService;
use PHPUnit\Framework\TestCase;

class ImportProductServiceTest extends TestCase
{
    /**
     * @dataProvider getCorrectData
     */
    public function testCorrectImportData(string $dataPreparation, array $expectedData): void
    {
        $importService = new ImportProductService();

        $preparedData = $importService->prepare($dataPreparation);

        $this->assertEquals($preparedData, $expectedData);
    }

    public function getCorrectData(): array
    {
        return [
            [
                'пр-кт Маркса 15	Препарат 4	30',
                ['Препарат 4', 'пр-кт Маркса 15', 30],
            ],
            [
                'Двеннадцатый Препарат	Д-Ковальчук дом 270	53',
                ['Двеннадцатый Препарат', 'Д-Ковальчук дом 270', 53],
            ],
        ];
    }

    /**
     * @dataProvider getIncorrectData
     */
    public function testIncorrectImportData(string $dataPreparation): void
    {
        $this->expectException(IncorrectImportProductException::class);

        $importService = new ImportProductService();
        $importService->prepare($dataPreparation);
    }

    public function getIncorrectData(): array
    {
        return [
            ['Двеннадцатый Препарат	Д-Ковальчук дом 270	Препарат'],
            ['Аптека	Препарат	Количество Количество'],
            ['Аптека	Препарат	Количество'],
            ['424'],
            ['fdf 424'],
            [''],
        ];
    }
}
