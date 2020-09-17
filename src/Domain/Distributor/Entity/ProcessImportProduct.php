<?php

namespace App\Domain\Distributor\Entity;

use App\Domain\Distributor\Exception\IncorrectImportProductException;
use App\Domain\Distributor\Service\ImportProductService;
use App\Domain\Pharmacy\Collection\PharmacyCollection;
use App\Domain\Pharmacy\Entity\Pharmacy;
use App\Domain\Preparation\Collection\PreparationCollection;
use App\Domain\Preparation\Collection\PreparationUndefinedCollection;
use App\Domain\Preparation\Entity\Preparation;
use App\Domain\Preparation\Entity\PreparationUndefined;
use App\Domain\Preparation\Entity\PreparationData;
use Doctrine\Common\Collections\ArrayCollection;
use Ramsey\Uuid\Uuid;
use SplFileObject;

class ProcessImportProduct
{
    private $distributor;
    private $file;
    private $collection;

    public function __construct(Distributor $distributor, SplFileObject $file)
    {
        $this->distributor = $distributor;
        $this->file = $file;
        $this->collection = new ArrayCollection();
    }

    public function prepare(array $preparations, array $preparationsUndefined, array $pharmacies): ArrayCollection
    {
        PreparationCollection::addInCollection($preparations, $this->collection);
        PreparationUndefinedCollection::addInCollection($preparationsUndefined, $this->collection);
        PharmacyCollection::addInCollection($pharmacies, $this->collection);

        return $this->processFile();
    }

    private function processFile(): ArrayCollection
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
            $preparedPreparationData = ImportProductService::prepare($data);

            $this->processPreparation($preparedPreparationData);
        } catch (IncorrectImportProductException $exception) {
            $this->addPreparationUndefined($data);
        }
    }

    private function processPreparation(PreparationData $preparedPreparationData): void
    {
        if (PreparationCollection::exist($this->collection, $preparedPreparationData->getName())) {
            $this->addPharmacyIfNotExistInCollection($preparedPreparationData);

            $this->increaseQuantity($preparedPreparationData);
        } else {
            $this->addDataInCollection($preparedPreparationData);
        }
    }

    private function addPharmacyIfNotExistInCollection(PreparationData $preparedPreparationData): void
    {
        if (!PharmacyCollection::exist($this->collection, $preparedPreparationData->getAddress())) {
            $this->addPharmacy($preparedPreparationData);
        }
    }

    private function increaseQuantity(PreparationData $preparedPreparationData): void
    {
        $preparation = PreparationCollection::getByValue($this->collection, $preparedPreparationData->getName());
        $preparation->addQuantity((int) $preparedPreparationData->getQuantity());
    }

    private function addDataInCollection(PreparationData $preparedPreparationData): void
    {
        if (PharmacyCollection::exist($this->collection, $preparedPreparationData->getAddress())) {
            $this->addPreparationWithExistingPharmacy($preparedPreparationData);
        } else {
            $this->addPreparationWithNotExistingPharmacy($preparedPreparationData);
        }
    }

    private function addPreparationWithExistingPharmacy(PreparationData $preparedPreparationData): void
    {
        $pharmacy = PharmacyCollection::getByValue($this->collection, $preparedPreparationData->getAddress());

        $this->addPreparation($preparedPreparationData, $pharmacy);
    }

    private function addPreparationWithNotExistingPharmacy(PreparationData $preparedPreparationData): void
    {
        $pharmacy = $this->addPharmacy($preparedPreparationData);

        $this->addPreparation($preparedPreparationData, $pharmacy);
    }

    private function addPreparation(PreparationData $preparedPreparationData, Pharmacy $pharmacy): Preparation
    {
        $preparation = self::createPreparation($preparedPreparationData, $pharmacy, $this->distributor);
        $this->collection->add($preparation);

        return $preparation;
    }

    private function addPharmacy(PreparationData $preparedPreparationData): Pharmacy
    {
        $pharmacy = new Pharmacy(Uuid::uuid4(), $preparedPreparationData->getAddress());
        $this->collection->add($pharmacy);

        return $pharmacy;
    }

    private function addPreparationUndefined(string $data): void
    {
        if ($data && !PreparationUndefinedCollection::exist($this->collection, $data)) {
            $preparationUndefined = new PreparationUndefined(Uuid::uuid4(), $data);
            $this->collection->add($preparationUndefined);
        }
    }

    private static function createPreparation(PreparationData $preparationData, Pharmacy $pharmacy, Distributor $distributor): Preparation
    {
        return new Preparation(
            Uuid::uuid4(),
            $preparationData->getName(),
            $preparationData->getQuantity(),
            $pharmacy,
            $distributor
        );
    }
}
