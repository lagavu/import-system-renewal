<?php

namespace App\Domain\Distributor\Command\Handler;

use App\Domain\Distributor\Command\ImportProductCommand;
use App\Domain\Distributor\Entity\ValueObject\ExistData;
use App\Domain\Distributor\Factory\FileDistributorFactory\FileDistributorFactory;
use App\Domain\Distributor\Repository\DistributorRepository;
use App\Domain\Pharmacy\Repository\PharmacyRepository;
use App\Domain\Preparation\Repository\PreparationUndefinedRepository;
use Doctrine\ORM\EntityManagerInterface;

class ImportProductHandler
{
    private $entityManager;
    private $distributorRepository;
    private $preparationUndefinedRepository;
    private $pharmacyRepository;
    private $fileDistributorFactory;

    public function __construct(
        EntityManagerInterface $entityManager,
        DistributorRepository $distributorRepository,
        PreparationUndefinedRepository $preparationUndefinedRepository,
        PharmacyRepository $pharmacyRepository,
        FileDistributorFactory $fileDistributorFactory
    ) {
        $this->entityManager = $entityManager;
        $this->distributorRepository = $distributorRepository;
        $this->preparationUndefinedRepository = $preparationUndefinedRepository;
        $this->pharmacyRepository = $pharmacyRepository;
        $this->fileDistributorFactory = $fileDistributorFactory;
    }

    public function handle(ImportProductCommand $importProductCommand): void
    {
        $distributor = $this->distributorRepository->get($importProductCommand->distributorName);

        $preparations = $distributor->getPreparations()->getValues();
        $preparationsUndefined = $this->preparationUndefinedRepository->findAll();
        $pharmacies = $this->pharmacyRepository->findAll();

        $existData = new ExistData($preparations, $preparationsUndefined, $pharmacies);

        $fileDistributorFactory = $this->fileDistributorFactory->get($importProductCommand->distributorName);
        $preparedData = $fileDistributorFactory
            ->buildFileDistributor()
            ->process($distributor, $importProductCommand->file, $existData);

        foreach ($preparedData as $data) {
            $this->entityManager->persist($data);
        }

        $this->entityManager->flush();
    }
}
