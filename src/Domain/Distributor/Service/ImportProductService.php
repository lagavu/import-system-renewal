<?php

namespace App\Domain\Distributor\Service;

use App\Domain\Distributor\Exception\IncorrectImportProductException;

class ImportProductService
{
    private const DELIMITER = "\t";
    private const REQUIRED_AMOUNT_OF_DATA = 3;
    private const CHECK_ON_PREPARATION = 'репарат';

    public const PREPARATION_NAME = 0;
    public const PHARMACY_ADDRESS = 1;
    public const PREPARATION_QUANTITY = 2;

    public static function prepare(string $data): array
    {
        $possibleData = self::makePossibleData($data);

        if (!self::checkForCorrectData($possibleData)) {
            throw new IncorrectImportProductException();
        }

        if (!self::checkForCorrectSequence($possibleData)) {
            return self::sorting($possibleData);
        }

        return $possibleData;
    }

    /**
     * @return string[]
     */
    private static function makePossibleData(string $data): array
    {
        return explode(self::DELIMITER, $data);
    }

    private static function checkForCorrectData(array $possibleData): bool
    {
        if (count($possibleData) === self::REQUIRED_AMOUNT_OF_DATA) {
            return self::checkPreparation($possibleData) && self::checkQuantity($possibleData);
        }

        return false;
    }

    private static function checkForCorrectSequence(array $possibleData): bool
    {
        return mb_stristr($possibleData[self::PREPARATION_NAME], self::CHECK_ON_PREPARATION) && self::checkQuantity($possibleData);
    }

    private static function checkPreparation(array $possibleData): bool
    {
        return mb_stristr($possibleData[self::PREPARATION_NAME], self::CHECK_ON_PREPARATION) || mb_stristr($possibleData[self::PHARMACY_ADDRESS], self::CHECK_ON_PREPARATION);
    }

    private static function checkQuantity(array $possibleData): bool
    {
        return ctype_digit(trim($possibleData[self::PREPARATION_QUANTITY]));
    }

    /**
     * @return string[]
     */
    private static function sorting(array $possibleData): array
    {
        $sortedData = [];

        $sortedData[] = $possibleData[self::PHARMACY_ADDRESS];
        $sortedData[] = $possibleData[self::PREPARATION_NAME];
        $sortedData[] = $possibleData[self::PREPARATION_QUANTITY];

        return $sortedData;
    }
}
