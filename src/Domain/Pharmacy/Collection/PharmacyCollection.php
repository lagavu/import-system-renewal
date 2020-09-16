<?php

namespace App\Domain\Pharmacy\Collection;

use App\Domain\Pharmacy\Entity\Pharmacy;
use Doctrine\Common\Collections\ArrayCollection;

class PharmacyCollection extends ArrayCollection
{
    public static function exist(ArrayCollection $collection, string $pharmacyAddress): bool
    {
        if ($collection->isEmpty()) {
            return false;
        }

        foreach (self::getPharmacies($collection) as $pharmacy) {
            if (!$pharmacy instanceof Pharmacy) {
                continue;
            }

            if ($pharmacy->getAddress() === $pharmacyAddress) {
                return true;
            }
        }

        return false;
    }

    public static function getByValue(ArrayCollection $collection, string $pharmacyAddress): Pharmacy
    {
        foreach (self::getPharmacies($collection) as $pharmacy) {
            if ($pharmacy->getAddress() === $pharmacyAddress) {
                return $pharmacy;
            }
        }
    }

    public static function addInCollection(array $pharmacies, ArrayCollection $collection): void
    {
        if ($pharmacies) {
            foreach ($pharmacies as $pharmacy) {
                $collection->add($pharmacy);
            }
        }
    }

    private static function getPharmacies(ArrayCollection $collection): ArrayCollection
    {
        $pharmacyCollection = new PharmacyCollection();

        foreach ($collection as $data) {
            if ($data instanceof Pharmacy) {
                $pharmacyCollection->add($data);
            }
        }

        return $pharmacyCollection;
    }
}
