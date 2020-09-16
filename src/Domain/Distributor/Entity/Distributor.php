<?php

namespace App\Domain\Distributor\Entity;

use App\Domain\Preparation\Entity\Preparation;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="distributors")
 */
class Distributor
{
    /**
     * @ORM\Id
     *
     * @ORM\Column(type="uuid")
     *
     * @var UuidInterface
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @var ArrayCollection|Preparation[]
     * @ORM\OneToMany(targetEntity="App\Domain\Preparation\Entity\Preparation", mappedBy="distributor", cascade={"persist", "remove"})
     */
    private $preparations;

    public function __construct(UuidInterface $id, string $name)
    {
        $this->id =$id;
        $this->name =$name;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPreparations(): PersistentCollection
    {
        return $this->preparations;
    }
}
