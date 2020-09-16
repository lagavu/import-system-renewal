<?php

namespace App\Domain\Preparation\Repository;

use App\Domain\Preparation\Entity\PreparationUndefined;
use Doctrine\ORM\EntityManagerInterface;

class PreparationUndefinedRepository
{
    /**
     * @var \Doctrine\ORM\EntityRepository
     */
    private $repository;
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->repository = $em->getRepository(PreparationUndefined::class);
        $this->em = $em;
    }

    public function findAll(): array
    {
        return $this->repository->findAll();
    }
}
