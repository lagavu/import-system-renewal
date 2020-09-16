<?php

namespace App\Domain\Pharmacy\Repository;

use App\Domain\Pharmacy\Entity\Pharmacy;
use Doctrine\ORM\EntityManagerInterface;

class PharmacyRepository
{
    /**
     * @var \Doctrine\ORM\EntityRepository
     */
    private $repository;
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->repository = $em->getRepository(Pharmacy::class);
        $this->em = $em;
    }

    public function get(string $address): ?Pharmacy
    {
        return $this->repository->findOneBy(['address' => $address]);
    }

    public function findAll(): array
    {
        return $this->repository->findAll();
    }
}
