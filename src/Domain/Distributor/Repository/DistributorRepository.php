<?php

namespace App\Domain\Distributor\Repository;

use App\Domain\Distributor\Entity\Distributor;
use Doctrine\ORM\EntityManagerInterface;

class DistributorRepository
{
    /**
     * @var \Doctrine\ORM\EntityRepository
     */
    private $repository;
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->repository = $em->getRepository(Distributor::class);
        $this->em = $em;
    }

    public function get(string $name): ?Distributor
    {
        return $this->repository->findOneBy(['name' => $name]);
    }

    public function findAll(): array
    {
        return $this->repository->findAll();
    }
}
