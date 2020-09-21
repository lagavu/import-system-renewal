<?php

namespace App\Domain\Distributor\Factory\FileDistributorFactory\FileDistributorTwo;

use App\Domain\Distributor\Factory\FileDistributorFactory\FileDistributorFactoryInterface;
use App\Domain\Distributor\Factory\FileDistributorFactory\ImportFileDistributorInterface;

class FileDistributorTwoFactory implements FileDistributorFactoryInterface
{
    public function buildFileDistributor(): ImportFileDistributorInterface
    {
        return new FileDistributorTwo();
    }
}
