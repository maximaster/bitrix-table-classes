<?php

declare(strict_types=1);

namespace Maximaster\BitrixTableClasses\Table\Iblock;

use Bitrix\Main\ORM\Data\AddResult;
use Bitrix\Main\ORM\Data\DeleteResult;
use Bitrix\Main\ORM\Data\UpdateResult;
use Bitrix\Main\ORM\Query\Result;

trait IblockTableTrait
{
    public static function getIblockId(): ?int
    {
        return null;
    }

    /**
     * @psalm-param array<non-empty-string, mixed> $data
     * @phpstan-ignore-next-line why:dependency
     */
    public static function add(array $data): AddResult
    {
        self::insertIblockId($data);

        return self::forwardCallToDataManager(__FUNCTION__, $data);
    }

    /**
     * @psalm-param array<non-empty-string, mixed> $data
     * @phpstan-ignore-next-line why:dependency
     */
    public static function update($primary, array $data): UpdateResult
    {
        self::insertIblockId($data);

        return self::forwardCallToDataManager(__FUNCTION__, $primary, $data);
    }

    public static function delete($primary): DeleteResult
    {
        return self::forwardCallToDataManager(__FUNCTION__, $primary);
    }

    /**
     * @psalm-param array<non-empty-string, mixed> $parameters
     * @psalm-return Result<int, array{string}>
     *
     * @phpstan-ignore-next-line why:dependency
     */
    public static function getList(array $parameters = [])
    {
        if (array_key_exists('filter', $parameters) === false) {
            $parameters['filter'] = [];
        }

        self::insertIblockId($parameters['filter']);

        return parent::getList($parameters);
    }

    /**
     * @psalm-param array<array-key, mixed> $data
     */
    private static function insertIblockId(array &$data): void
    {
        $iblockId = static::getIblockId();
        if ($iblockId !== null) {
            $data['IBLOCK_ID'] = $iblockId;
        }
    }
}
