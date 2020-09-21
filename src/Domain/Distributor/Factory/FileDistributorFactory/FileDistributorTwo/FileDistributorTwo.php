<?php

namespace App\Domain\Distributor\Factory\FileDistributorFactory\FileDistributorTwo;

use App\Domain\Distributor\Entity\Distributor;
use App\Domain\Distributor\Entity\ValueObject\ExistData;
use App\Domain\Distributor\Exception\IncorrectImportProductException;
use App\Domain\Distributor\Factory\FileDistributorFactory\ImportFileDistributorInterface;
use App\Domain\Distributor\Service\ImportProductService;
use App\Domain\Pharmacy\Entity\ValueObject\PharmacyCompliance;
use App\Domain\Preparation\Entity\PreparationData;
use App\Domain\Preparation\Entity\PreparationUndefined;
use App\Domain\Preparation\Entity\ValueObject\PreparationCompliance;
use Doctrine\Common\Collections\ArrayCollection;
use Ramsey\Uuid\Uuid;
use SplFileObject;
use Symfony\Component\HttpFoundation\File\File;

class FileDistributorTwo implements ImportFileDistributorInterface
{
    private $collection;

    public function __construct()
    {
        $this->collection = new ArrayCollection();
    }

    public function process(Distributor $distributor, File $file, ExistData $existData): ArrayCollection
    {
        $preparedFileForIteration = new SplFileObject($file);
        $this->addInCurrentCollection($existData);

        while (!$preparedFileForIteration->eof()) {
            $data = $preparedFileForIteration->fgets();
            $this->processData($data, $distributor);
        }

        return $this->collection;
    }

    private function addInCurrentCollection(ExistData $existData): void
    {
        $allData = array_merge($existData->getPreparations(), $existData->getPreparationsUndefined(), $existData->getPharmacies());

        foreach ($allData as $data) {
            $this->collection->add($data);
        }
    }

    private function processData(string $data, Distributor $distributor): void
    {
        try {
            $preparationData = ImportProductService::prepare($data);
            $this->processPreparation(self::transform($preparationData), $distributor);
        } catch (IncorrectImportProductException $exception) {
            $this->addPreparationUndefined($data);
        }
    }

    private function processPreparation(PreparationData $preparationData, Distributor $distributor): void
    {
        if (!$this->collection->isEmpty() && $preparationData->getCurrentPreparation($this->collection)) {
            $preparationData->addPharmacyIfNotExists($this->collection);

            $preparationData->increaseQuantity($this->collection);
        } else {
            $this->addDataInCollection($preparationData, $distributor);
        }
    }

    private function addDataInCollection(PreparationData $preparationData, Distributor $distributor): void
    {
        if ($preparationData->getCurrentPharmacy($this->collection)) {
            $this->addPreparationWithExistingPharmacy($preparationData, $distributor);
        } else {
            $this->addPreparationWithNotExistingPharmacy($preparationData, $distributor);
        }
    }

    private function addPreparationWithExistingPharmacy(PreparationData $preparationData, Distributor $distributor): void
    {
        $pharmacy = $preparationData->getCurrentPharmacy($this->collection);
        $preparationData->addPreparation($this->collection, $pharmacy, $distributor);
    }

    private function addPreparationWithNotExistingPharmacy(PreparationData $preparationData, Distributor $distributor): void
    {
        $pharmacy = $preparationData->addPharmacy($this->collection);
        $preparationData->addPreparation($this->collection, $pharmacy, $distributor);
    }

    private function addPreparationUndefined(string $data): void
    {
        if ($data && !$this->getCurrentPreparationUndefined($data)) {
            $preparationUndefined = new PreparationUndefined(Uuid::uuid4(), $data);
            $this->collection->add($preparationUndefined);
        }
    }

    private function getCurrentPreparationUndefined(string $data): ?PreparationUndefined
    {
        $preparationUndefined = $this->collection->filter(function ($preparationUndefined) use ($data) {
            if ($preparationUndefined instanceof PreparationUndefined && $preparationUndefined->getName() === $data) {
                return $preparationUndefined;
            }
        });

        return !$preparationUndefined->isEmpty() ? $preparationUndefined->first() : null;
    }

    private static function transform(array $possibleData): PreparationData
    {
        $preparationName = $possibleData[ImportProductService::PREPARATION_NAME];
        $pharmacyAddress = $possibleData[ImportProductService::PHARMACY_ADDRESS];

        $possibleData[ImportProductService::PREPARATION_NAME] = array_search($preparationName, PreparationCompliance::NAMES);
        $possibleData[ImportProductService::PHARMACY_ADDRESS] = array_search($pharmacyAddress, PharmacyCompliance::ADDRESSES);

        return self::createPreparationData($possibleData);
    }

    private static function createPreparationData(array $possibleData): PreparationData
    {
        return new PreparationData(
            $possibleData[ImportProductService::PREPARATION_NAME],
            $possibleData[ImportProductService::PHARMACY_ADDRESS],
            (int) $possibleData[ImportProductService::PREPARATION_QUANTITY]
        );
    }
}
