<?php

namespace App\Domain\Preparation\Entity;

use App\Domain\Distributor\Entity\Distributor;
use App\Domain\Pharmacy\Entity\Pharmacy;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="preparations")
 */
class Preparation
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
     * @ORM\Column(type="integer", options={"default": 0})
     */
    private $quantity;

    /**
     * @ORM\ManyToOne(targetEntity="App\Domain\Pharmacy\Entity\Pharmacy", inversedBy="preparations")
     * @ORM\JoinColumn(name="pharmacy_id", referencedColumnName="id", nullable=false)
     */
    private $pharmacy;

    /**
     * @ORM\ManyToOne(targetEntity="App\Domain\Distributor\Entity\Distributor", inversedBy="preparations")
     * @ORM\JoinColumn(name="distributor_id", referencedColumnName="id", nullable=false)
     */
    private $distributor;

    public function __construct(
        UuidInterface $id,
        string $name,
        string $quantity,
        Pharmacy $pharmacy,
        Distributor $distributor)
    {
        $this->id = $id;
        $this->name = $name;
        $this->quantity = $quantity;
        $this->pharmacy = $pharmacy;
        $this->distributor = $distributor;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getPharmacy(): Pharmacy
    {
        return $this->pharmacy;
    }

    public function addQuantity(int $quantity): Preparation
    {
        $this->quantity += $quantity;

        return $this;
    }

    public function getDistributor(): Distributor
    {
        return $this->distributor;
    }
}
