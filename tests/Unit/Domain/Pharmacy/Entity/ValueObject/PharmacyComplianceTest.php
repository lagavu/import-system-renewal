<?php

namespace App\Tests\Unit\Domain\Pharmacy\Entity\ValueObject;

use App\Domain\Pharmacy\Entity\ValueObject\PharmacyCompliance;
use PHPUnit\Framework\TestCase;

class PharmacyComplianceTest extends TestCase
{
    public function testSuccessfulCompliancePharmacy(): void
    {
        $expectedPharmacyComplianceAddresses = [
            'пр-кт Маркса 15' => 'Маркса дом 15 стр 1',
            'ул Гоголя 1' => 'Гоголя ул дом 1',
            'ул Блюхера 4' => 'Блюхера ул дом 4',
            'ул Никитина 73' => 'Никитина дом 73',
            'ул Зорге 3' => 'Зорге ул дом 3',
            'ул Кирова 32' => 'Кирова улица дом 32',
            'ул Дуси Ковальчук 270' => 'Д-Ковальчук дом 270',
        ];

        $this->assertEquals($expectedPharmacyComplianceAddresses, PharmacyCompliance::ADDRESSES);
    }
}
