<?php

namespace App\Domain\Distributor\Factory\FileDistributorFactory;

use App\Domain\Distributor\Factory\FileDistributorFactory\FileDistributorOne\FileDistributorOneFactory;
use App\Domain\Distributor\Factory\FileDistributorFactory\FileDistributorTwo\FileDistributorTwoFactory;
use Exception;

class FileDistributorFactory
{
    public function get(string $distributorName): FileDistributorFactoryInterface
    {
        switch ($distributorName) {
            case 'Дистрибьютер 1':
                $fileDistributorFactory = new FileDistributorOneFactory();
                break;
            case 'Дистрибьютер 2':
                $fileDistributorFactory = new FileDistributorTwoFactory();
                break;
            default:
                throw new Exception(sprintf('Неизвестный тип фабрики %s', $distributorName));
        }

        return $fileDistributorFactory;
    }
}
