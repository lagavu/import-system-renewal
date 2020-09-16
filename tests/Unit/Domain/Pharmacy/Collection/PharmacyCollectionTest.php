<?php

namespace App\Tests\Unit\Domain\Pharmacy\Collection;

use App\Domain\Pharmacy\Collection\PharmacyCollection;
use App\Domain\Pharmacy\Entity\Pharmacy;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class PharmacyCollectionTest extends TestCase
{
    /** @var PharmacyCollection */
    private $pharmacyCollection;
    /** @var ArrayCollection */
    private $collection;

    protected function setUp(): void
    {
        parent::setUp();

        $this->pharmacyCollection = new PharmacyCollection();
        $this->collection = new ArrayCollection();
        $this->collection->add($this->createPharmacy('ул Зорге 3'));
        $this->collection->add($this->createPharmacy('ул Зорге 3'));
    }

    protected function tearDown(): void
    {
        unset(
            $this->pharmacyCollection,
            $this->collection
        );

        parent::tearDown();
    }

    public function testPharmacyAddressNotExistsWhenCollectionEmpty(): void
    {
        $pharmacyAddress = 'ул Кирова 32';

        $this->assertFalse($this->pharmacyCollection->exist($this->collection, $pharmacyAddress));
    }

    public function testPharmacyAddressesNotExistsWhenCollectionNotContainAddress(): void
    {
        $pharmacyAddresses = 'ул Кирова 32';

        $this->collection->add($this->createPharmacy('ул Зорге 3'));

        $this->assertFalse($this->pharmacyCollection->exist($this->collection, $pharmacyAddresses));
    }

    public function testPharmacyAddressesExists(): void
    {
        $pharmacyAddresses = 'ул Кирова 32';

        $this->collection->add($this->createPharmacy('ул Кирова 32'));

        $this->assertTrue($this->pharmacyCollection->exist($this->collection, $pharmacyAddresses));
    }

    public function testGetByValuePharmacy(): void
    {
        $pharmacyAddresses = 'ул Кирова 32';

        $expectedPharmacy = $this->createPharmacy($pharmacyAddresses);
        $this->collection->add($expectedPharmacy);

        $this->assertEquals($expectedPharmacy, $this->pharmacyCollection->getByValue($this->collection, $pharmacyAddresses));
    }

    private function createPharmacy(string $addresses): Pharmacy
    {
        $stub = $this->createMock(Pharmacy::class);
        $stub
            ->method('getAddress')
            ->willReturn($addresses);

        return $stub;
    }
}
