<?php

namespace App\Tests\Unit\Domain\Preparation\Entity\ValueObject;

use App\Domain\Preparation\Entity\ValueObject\PreparationCompliance;
use PHPUnit\Framework\TestCase;

class PreparationComplianceTest extends TestCase
{
    public function testSuccessfulCompliancePreparations(): void
    {
        $expectedPreparationComplianceNames = [
            'Препарат 1' => 'Первый препарат',
            'Препарат 2' => 'Второй препарат',
            'Препарат 3' => 'Третий препарат',
            'Препарат 4' => 'Четвертый препарат',
            'Препарат 5' => 'Пятый Препарат',
            'Препарат 6' => 'Шестой Препарат',
            'Препарат 7' => 'Седьмой Препарат',
            'Препарат 8' => 'Восьмой Препарат',
            'Препарат 9' => 'Девятый Препарат',
            'Препарат 10' => 'Десятый Препарат',
            'Препарат 11' => 'Одиннадцатый Препарат',
            'Препарат 12' => 'Двеннадцатый Препарат',

        ];

        $this->assertEquals($expectedPreparationComplianceNames, PreparationCompliance::NAMES);
    }
}
