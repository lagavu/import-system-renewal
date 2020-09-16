<?php

namespace App\Domain\Pharmacy\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="pharmacies")
 */
class Pharmacy
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
     * @ORM\Column(type="string")
     */
    private $address;

    /**
     * @ORM\OneToMany(targetEntity="App\Domain\Preparation\Entity\Preparation", mappedBy="pharmacy", cascade={"persist", "remove"})
     */
    private $preparations;

    public function __construct(UuidInterface $id, string $address)
    {
        $this->id = $id;
        $this->address = $address;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getPreparations(): PersistentCollection
    {
        return $this->preparations;
    }

    public function getCommonQuantity(array $preparations): int
    {
        $commonQuantity = 0;

        foreach ($preparations as $preparation) {
            $commonQuantity += $preparation->getQuantity();
        }

        return $commonQuantity;
    }
}
