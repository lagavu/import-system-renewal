<?php

namespace App\Domain\Distributor\Command\Handler;

use App\Domain\Distributor\Command\ImportProductCommand;
use App\Domain\Distributor\Entity\ProcessImportProduct;
use App\Domain\Distributor\Entity\ValueObject\ExistData;
use App\Domain\Distributor\Repository\DistributorRepository;
use App\Domain\Pharmacy\Repository\PharmacyRepository;
use App\Domain\Preparation\Repository\PreparationUndefinedRepository;
use Doctrine\ORM\EntityManagerInterface;
use SplFileObject;

class ImportProductHandler
{
    private $entityManager;
    private $distributorRepository;
    private $preparationUndefinedRepository;
    private $pharmacyRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        DistributorRepository $distributorRepository,
        PreparationUndefinedRepository $preparationUndefinedRepository,
        PharmacyRepository $pharmacyRepository
    ) {
        $this->entityManager = $entityManager;
        $this->distributorRepository = $distributorRepository;
        $this->preparationUndefinedRepository = $preparationUndefinedRepository;
        $this->pharmacyRepository = $pharmacyRepository;
    }

    public function handle(ImportProductCommand $importProductCommand): void
    {
        $distributor = $this->distributorRepository->get($importProductCommand->distributorName);

        $preparations = $distributor->getPreparations()->getValues();
        $preparationsUndefined = $this->preparationUndefinedRepository->findAll();
        $pharmacies = $this->pharmacyRepository->findAll();

        $existData = new ExistData($preparations, $preparationsUndefined, $pharmacies);
        $processImportProduct = new ProcessImportProduct(
            $distributor,
            new SplFileObject($importProductCommand->file),
            $existData->getCollectionWithAllData()
        );

        $preparedData = $processImportProduct->process();

        foreach ($preparedData as $data) {
            $this->entityManager->persist($data);
        }

        $this->entityManager->flush();
    }
}
