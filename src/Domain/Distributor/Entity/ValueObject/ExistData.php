<?php

namespace App\Domain\Distributor\Entity\ValueObject;

use Doctrine\Common\Collections\ArrayCollection;

class ExistData
{
    private $preparations;
    private $preparationsUndefined;
    private $pharmacies;

    public function __construct(array $preparations, array $preparationsUndefined, array $pharmacies)
    {
        $this->preparations = $preparations;
        $this->preparationsUndefined = $preparationsUndefined;
        $this->pharmacies = $pharmacies;
    }

    public function getPreparations(): array
    {
        return $this->preparations;
    }

    public function getPreparationsUndefined(): array
    {
        return $this->preparationsUndefined;
    }

    public function getPharmacies(): array
    {
        return $this->pharmacies;
    }

    public function merge(): array
    {
        return array_merge($this->preparations, $this->preparationsUndefined, $this->pharmacies);
    }

    public function getCollectionWithAllData(): ArrayCollection
    {
        $collection = new ArrayCollection();

        foreach ($this->merge() as $data) {
            $collection->add($data);
        }

        return $collection;
    }
}
