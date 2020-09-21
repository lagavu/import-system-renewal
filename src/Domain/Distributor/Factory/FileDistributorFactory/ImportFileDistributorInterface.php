<?php

namespace App\Domain\Distributor\Factory\FileDistributorFactory;

use App\Domain\Distributor\Entity\Distributor;
use App\Domain\Distributor\Entity\ValueObject\ExistData;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\HttpFoundation\File\File;

interface ImportFileDistributorInterface
{
    public function process(Distributor $distributor, File $file, ExistData $existData): ArrayCollection;
}
