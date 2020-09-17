<?php

namespace App\Tests\Unit\Domain\Distributor\Entity;

use App\Domain\Distributor\Entity\Distributor;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class DistributorTest extends TestCase
{
    public function testSuccessfulCreationDistributor(): void
    {
        $distributor = new Distributor(
            $id = Uuid::uuid4(),
            $name = 'Дистрибьютер 1'
        );

        $this->assertEquals($id, $distributor->getId());
        $this->assertEquals($name, $distributor->getName());
    }
}
