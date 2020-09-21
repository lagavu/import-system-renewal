<?php

namespace App\Tests\Unit\Domain\Distributor\Factory\FileDistributorFactory;

use App\Domain\Distributor\Factory\FileDistributorFactory\FileDistributorFactory;
use App\Domain\Distributor\Factory\FileDistributorFactory\FileDistributorOne\FileDistributorOneFactory;
use App\Domain\Distributor\Factory\FileDistributorFactory\FileDistributorTwo\FileDistributorTwoFactory;
use PHPUnit\Framework\TestCase;

class FileDistributorFactoryTest extends TestCase
{
    public function testSuccessfulFactoryCreationForFileDistributorOne(): void
    {
        $distributorName = 'Дистрибьютер 1';

        $fileDistributorFactory = new FileDistributorFactory();
        $fileDistributorOneFactory = $fileDistributorFactory->get($distributorName);

        $this->assertInstanceOf(FileDistributorOneFactory::class, $fileDistributorOneFactory);
    }

    public function testSuccessfulFactoryCreationForFileDistributorTwo(): void
    {
        $distributorName = 'Дистрибьютер 2';

        $fileDistributorFactory = new FileDistributorFactory();
        $fileDistributorTwoFactory = $fileDistributorFactory->get($distributorName);

        $this->assertInstanceOf(FileDistributorTwoFactory::class, $fileDistributorTwoFactory);
    }
}
