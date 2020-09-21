<?php

namespace App\Tests\Unit\Domain\Distributor\Factory\FileDistributorFactory\FileDistributorOne;

use App\Domain\Distributor\Factory\FileDistributorFactory\FileDistributorOne\FileDistributorOne;
use App\Domain\Distributor\Factory\FileDistributorFactory\FileDistributorOne\FileDistributorOneFactory;
use PHPUnit\Framework\TestCase;

class FileDistributorOneFactoryTest extends TestCase
{
    public function testSuccessfulCreationFileDistributorOne(): void
    {
        $fileDistributorOneFactory = new FileDistributorOneFactory();
        $fileDistributorOne = $fileDistributorOneFactory->buildFileDistributor();

        $this->assertInstanceOf(FileDistributorOne::class, $fileDistributorOne);
    }
}
