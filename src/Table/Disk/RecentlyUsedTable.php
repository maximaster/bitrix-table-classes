<?php

declare(strict_types=1);

namespace Maximaster\BitrixTableClasses\Table\Disk;

use Bitrix\Disk\Internals\DataManager;
use Bitrix\Main\Application;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\DB\MysqlCommonConnection;
use Bitrix\Main\DB\SqlQueryException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\ORM\Fields\DatetimeField;
use Bitrix\Main\ORM\Fields\Field;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\SystemException;
use Bitrix\Main\Type\DateTime;
use Maximaster\BitrixTableClasses\Mixins\Dmbat;

/**
 * Таблица недавно использованных объектов диска.
 * Не наследует от @Bitrix\Disk\Internals\RecentlyUsedTable, т.к. oн final.
 */
class RecentlyUsedTable extends DataManager
{
    use Dmbat;

    /** @var string */
    public const ID = 'ID';

    /** @var string */
    public const USER_ID = 'USER_ID';

    /** @var string */
    public const OBJECT_ID = 'OBJECT_ID';

    /** @var string */
    public const CREATE_TIME = 'CREATE_TIME';

    /** @var int */
    public const MAX_COUNT_FOR_USER = 50;

    /**
     * @see \Bitrix\Disk\Internals\RecentlyUsedTable::getTableName()
     */
    public static function getTableName(): string
    {
        return 'b_disk_recently_used';
    }

    /**
     * @see \Bitrix\Disk\Internals\RecentlyUsedTable::getMap()
     *
     * @psalm-return array<non-empty-string|int, Field|array<non-empty-string, mixed>>
     */
    public static function getMap(): array
    {
        return [
            self::ID => (new IntegerField(self::ID))
                ->configurePrimary(true)
                ->configureAutocomplete(true),
            self::USER_ID => (new IntegerField(self::USER_ID))
                ->configureRequired(true),
            self::OBJECT_ID => (new IntegerField(self::OBJECT_ID))
                ->configureRequired(true),
            self::CREATE_TIME => (new DatetimeField(self::CREATE_TIME))
                ->configureRequired(true)
                ->configureDefaultValue(static fn () => new DateTime()),
        ];
    }

    /**
     * @see \Bitrix\Disk\Internals\RecentlyUsedTable::deleteBatch()
     *
     * @psalm-param array<array-key, mixed> $filter
     */
    public static function deleteBatch(array $filter): void
    {
        parent::deleteBatch($filter);
    }

    /**
     * @see \Bitrix\Disk\Internals\RecentlyUsedTable::insertBatch()
     *
     * @psalm-param list<array<non-empty-string, mixed>> $items
     * @phpstan-ignore-next-line why:dependency
     */
    public static function insertBatch(array $items): void
    {
        parent::insertBatch($items);
    }

    /**
     * @see \Bitrix\Disk\Internals\RecentlyUsedTable::deleteOldObjects()
     *
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SqlQueryException
     * @throws SystemException
     */
    public static function deleteOldObjects(int $userId): void
    {
        $offset = self::MAX_COUNT_FOR_USER - 1;
        $connection = Application::getConnection();

        if ($connection instanceof MysqlCommonConnection) {
            $connection->queryExecute("
				DELETE t
				FROM
				    b_disk_recently_used AS t
			    JOIN
				( SELECT ID
				  FROM b_disk_recently_used
				  WHERE USER_ID = {$userId}
				  ORDER BY ID DESC
				  LIMIT 1 OFFSET {$offset}
				) tlimit ON t.ID < tlimit.ID AND t.USER_ID = {$userId}
			");

            return;
        }

        $result = static::getList([
            'select' => ['ID'],
            'filter' => ['USER_ID' => $userId],
            'order' => ['ID' => 'DESC'],
            'limit' => 1,
            'offset' => $offset,
        ])->fetch();

        $id = $result === false ? null : (int) $result['ID'];

        if ($id !== null) {
            $connection->queryExecute("DELETE FROM b_disk_recently_used WHERE ID < {$id} AND USER_ID = {$userId}");
        }
    }
}
