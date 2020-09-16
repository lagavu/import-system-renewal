<?php

namespace App\Domain\Preparation\Collection;

use App\Domain\Preparation\Entity\Preparation;
use Doctrine\Common\Collections\ArrayCollection;

class PreparationCollection extends ArrayCollection
{
    public static function exist(ArrayCollection $collection, string $preparationName): bool
    {
        if ($collection->isEmpty()) {
            return false;
        }

        foreach (self::getPreparations($collection) as $preparation) {
            if ($preparation->getName() === $preparationName) {
                return true;
            }
        }

        return false;
    }

    public static function getByValue(ArrayCollection $collection, string $preparationName): Preparation
    {
        foreach (self::getPreparations($collection) as $preparation) {
            if ($preparation->getName() === $preparationName) {
                return $preparation;
            }
        }
    }

    public static function addInCollection(array $preparations, ArrayCollection $collection): void
    {
        if ($preparations) {
            foreach ($preparations as $preparation) {
                $collection->add($preparation);
            }
        }
    }

    private static function getPreparations(ArrayCollection $collection): PreparationCollection
    {
        $preparationCollection = new PreparationCollection();

        foreach ($collection as $data) {
            if ($data instanceof Preparation) {
                $preparationCollection->add($data);
            }
        }

        return $preparationCollection;
    }
}
