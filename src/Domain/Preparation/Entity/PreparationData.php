<?php

namespace App\Domain\Preparation\Entity;

use App\Domain\Distributor\Entity\Distributor;
use App\Domain\Pharmacy\Entity\Pharmacy;
use Doctrine\Common\Collections\ArrayCollection;
use Ramsey\Uuid\Uuid;

class PreparationData
{
    private $name;
    private $address;
    private $quantity;

    public function __construct(string $name, string $address, int $quantity)
    {
        $this->name = $name;
        $this->address = $address;
        $this->quantity = $quantity;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getCurrentPreparation(ArrayCollection $collection): ?Preparation
    {
        $preparation = $collection->filter(function ($preparation) {
            if ($preparation instanceof Preparation && $preparation->getName() === $this->getName() && $preparation->getPharmacy()->getAddress() === $this->getAddress()) {
                return $preparation;
            }
        });

        return !$preparation->isEmpty() ? $preparation->first() : null;
    }

    public function getCurrentPharmacy(ArrayCollection $collection): ?Pharmacy
    {
        $pharmacy = $collection->filter(function ($pharmacy) {
            if ($pharmacy instanceof Pharmacy && $pharmacy->getAddress() === $this->getAddress()) {
                return $pharmacy;
            }
        });

        return !$pharmacy->isEmpty() ? $pharmacy->first() : null;
    }

    public function addPharmacyIfNotExists(ArrayCollection $collection): void
    {
        if (!$this->getCurrentPharmacy($collection)) {
            $this->addPharmacy($collection);
        }
    }

    public function addPharmacy(ArrayCollection $collection): Pharmacy
    {
        $pharmacy = new Pharmacy(Uuid::uuid4(), $this->getAddress());
        $collection->add($pharmacy);

        return $pharmacy;
    }

    public function increaseQuantity(ArrayCollection $collection): void
    {
        $preparation = $this->getCurrentPreparation($collection);

        $preparation->addQuantity($this->getQuantity());
    }

    public function addPreparation(ArrayCollection $collection, Pharmacy $pharmacy, Distributor $distributor): Preparation
    {
        $preparation = $this->createPreparation($pharmacy, $distributor);
        $collection->add($preparation);

        return $preparation;
    }

    private function createPreparation(Pharmacy $pharmacy, Distributor $distributor): Preparation
    {
        return new Preparation(
            Uuid::uuid4(),
            $this->getName(),
            $this->getQuantity(),
            $pharmacy,
            $distributor
        );
    }
}
