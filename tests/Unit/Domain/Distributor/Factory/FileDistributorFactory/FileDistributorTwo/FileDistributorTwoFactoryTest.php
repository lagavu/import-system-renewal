<?php

namespace App\Tests\Unit\Domain\Distributor\Factory\FileDistributorFactory\FileDistributorTwo;

use App\Domain\Distributor\Factory\FileDistributorFactory\FileDistributorTwo\FileDistributorTwo;
use App\Domain\Distributor\Factory\FileDistributorFactory\FileDistributorTwo\FileDistributorTwoFactory;
use PHPUnit\Framework\TestCase;

class FileDistributorTwoFactoryTest extends TestCase
{
    public function testSuccessfulCreationFileDistributorTwo(): void
    {
        $fileDistributorTwoFactory = new FileDistributorTwoFactory();
        $fileDistributorTwo = $fileDistributorTwoFactory->buildFileDistributor();

        $this->assertInstanceOf(FileDistributorTwo::class, $fileDistributorTwo);
    }
}
