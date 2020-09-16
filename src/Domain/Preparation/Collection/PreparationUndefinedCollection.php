<?php

namespace App\Domain\Preparation\Collection;

use App\Domain\Preparation\Entity\PreparationUndefined;
use Doctrine\Common\Collections\ArrayCollection;

class PreparationUndefinedCollection extends ArrayCollection
{
    public static function exist(ArrayCollection $collection, string $preparationUndefinedName): bool
    {
        if ($collection->isEmpty()) {
            return false;
        }

        foreach (self::getPreparationsUndefined($collection) as $preparationUndefined) {
            if ($preparationUndefined->getName() === $preparationUndefinedName) {
                return true;
            }
        }

        return false;
    }

    public static function addInCollection(array $preparationsUndefined, ArrayCollection $collection): void
    {
        if ($preparationsUndefined) {
            foreach ($preparationsUndefined as $preparationUndefined) {
                $collection->add($preparationUndefined);
            }
        }
    }

    private static function getPreparationsUndefined(ArrayCollection $collection): PreparationUndefinedCollection
    {
        $preparationUndefinedCollection = new PreparationUndefinedCollection();

        foreach ($collection as $data) {
            if ($data instanceof PreparationUndefined) {
                $preparationUndefinedCollection->add($data);
            }
        }

        return $preparationUndefinedCollection;
    }
}
