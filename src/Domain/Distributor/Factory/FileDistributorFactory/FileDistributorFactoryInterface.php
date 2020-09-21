<?php

namespace App\Domain\Distributor\Factory\FileDistributorFactory;

interface FileDistributorFactoryInterface
{
    public function get(string $distributorName): FileDistributorFactoryInterface;
}
