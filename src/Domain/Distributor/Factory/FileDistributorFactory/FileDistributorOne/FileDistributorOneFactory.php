<?php

namespace App\Domain\Distributor\Factory\FileDistributorFactory\FileDistributorOne;

use App\Domain\Distributor\Factory\FileDistributorFactory\FileDistributorFactoryInterface;
use App\Domain\Distributor\Factory\FileDistributorFactory\ImportFileDistributorInterface;

class FileDistributorOneFactory implements FileDistributorFactoryInterface
{
    public function buildFileDistributor(): ImportFileDistributorInterface
    {
        return new FileDistributorOne();
    }
}
