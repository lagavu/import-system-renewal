<?php

namespace App\Domain\Distributor\Entity;

use App\Domain\Distributor\Exception\IncorrectImportProductException;
use App\Domain\Distributor\Service\ImportProductService;
use App\Domain\Preparation\Entity\PreparationData;
use App\Domain\Preparation\Entity\PreparationUndefined;
use Doctrine\Common\Collections\ArrayCollection;
use Ramsey\Uuid\Uuid;
use SplFileObject;

class ProcessImportProduct
{
    private $distributor;
    private $file;
    private $collection;

    public function __construct(Distributor $distributor, SplFileObject $file, ArrayCollection $collection)
    {
        $this->distributor = $distributor;
        $this->file = $file;
        $this->collection = $collection;
    }

    public function process(): ArrayCollection
    {
        while (!$this->file->eof()) {
            $data = $this->file->fgets();
            $this->processData($data);
        }

        return $this->collection;
    }

    private function processData(string $data): void
    {
        try {
            $preparationData = ImportProductService::prepare($data);
            $this->processPreparation($preparationData);
        } catch (IncorrectImportProductException $exception) {
            $this->addPreparationUndefined($data);
        }
    }

    private function processPreparation(PreparationData $preparationData): void
    {
        if (!$this->collection->isEmpty() && $preparationData->getCurrentPreparation($this->collection)) {
            $preparationData->addPharmacyIfNotExists($this->collection);

            $preparationData->increaseQuantity($this->collection);
        } else {
            $this->addDataInCollection($preparationData);
        }
    }

    public function addDataInCollection(PreparationData $preparationData): void
    {
        if ($preparationData->getCurrentPharmacy($this->collection)) {
            $this->addPreparationWithExistingPharmacy($preparationData);
        } else {
            $this->addPreparationWithNotExistingPharmacy($preparationData);
        }
    }

    private function addPreparationWithExistingPharmacy(PreparationData $preparationData): void
    {
        $pharmacy = $preparationData->getCurrentPharmacy($this->collection);

        $preparationData->addPreparation($this->collection, $pharmacy, $this->distributor);
    }

    private function addPreparationWithNotExistingPharmacy(PreparationData $preparationData): void
    {
        $pharmacy = $preparationData->addPharmacy($this->collection);

        $preparationData->addPreparation($this->collection, $pharmacy, $this->distributor);
    }

    private function addPreparationUndefined(string $data): void
    {
        if ($data && !$this->getCurrentPreparationUndefined($this->collection)) {
            $preparationUndefined = new PreparationUndefined(Uuid::uuid4(), $data);
            $this->collection->add($preparationUndefined);
        }
    }

    public function getCurrentPreparationUndefined(ArrayCollection $collection): ?PreparationUndefined
    {
        $preparationUndefined = $collection->filter(function ($preparationUndefined) {
            if ($preparationUndefined instanceof PreparationUndefined) {
                return $preparationUndefined;
            }
        });

        return !$preparationUndefined->isEmpty() ? $preparationUndefined->first() : null;
    }
}
