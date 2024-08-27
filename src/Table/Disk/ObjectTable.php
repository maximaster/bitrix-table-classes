<?php

declare(strict_types=1);

namespace Maximaster\BitrixTableClasses\Table\Disk;

use Bitrix\Disk\Internals\ObjectTable as BaseObjectTable;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\ORM\Fields\Field;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;
use Bitrix\Main\SystemException;
use Maximaster\BitrixEnums\Main\Orm\JoinType;
use Maximaster\BitrixTableClasses\Mixins\Dmbat;
use Maximaster\BitrixTableClasses\Mixins\TableMapMergerTrait;

/**
 * Переопределяем базовый класс ObjectTable модуля "Disk".
 * Переопределение делается для того, чтобы предоставить возможность создавать общие пользовательские поля для объекта.
 */
class ObjectTable extends BaseObjectTable
{
    use Dmbat;
    use TableMapMergerTrait;

    public const ID = 'ID';
    public const NAME = 'NAME';
    public const TYPE = 'TYPE';
    public const CODE = 'CODE';
    public const XML_ID = 'XML_ID';
    public const STORAGE_ID = 'STORAGE_ID';
    public const REAL_OBJECT_ID = 'REAL_OBJECT_ID';
    public const REAL_OBJECT = 'REAL_OBJECT';
    public const PARENT_ID = 'PARENT_ID';
    public const CONTENT_PROVIDER = 'CONTENT_PROVIDER';
    public const CREATE_TIME = 'CREATE_TIME';
    public const UPDATE_TIME = 'UPDATE_TIME';
    public const SYNC_UPDATE_TIME = 'SYNC_UPDATE_TIME';
    public const DELETE_TIME = 'DELETE_TIME';
    public const CREATED_BY = 'CREATED_BY';
    public const UPDATED_BY = 'UPDATED_BY';
    public const DELETED_BY = 'DELETED_BY';
    public const GLOBAL_CONTENT_VERSION = 'GLOBAL_CONTENT_VERSION';
    public const FILE_ID = 'FILE_ID';
    public const TYPE_FILE = 'TYPE_FILE';
    public const SIZE = 'SIZE';
    public const EXTERNAL_HASH = 'EXTERNAL_HASH';
    public const DELETED_TYPE = 'DELETED_TYPE';
    public const ETAG = 'ETAG';
    public const PREVIEW_ID = 'PREVIEW_ID';
    public const VIEW_ID = 'VIEW_ID';
    public const SEARCH_INDEX = 'SEARCH_INDEX';
    public const CREATE_USER = 'CREATE_USER';
    public const UPDATE_USER = 'UPDATE_USER';
    public const DELETE_USER = 'DELETE_USER';
    public const STORAGE = 'STORAGE';
    public const PARENT = 'PARENT';
    public const FILE_CONTENT = 'FILE_CONTENT';
    public const PATH_CHILD = 'PATH_CHILD';

    /** @var string */
    public const UF_ENTITY_ID = 'DISK_OBJECT';

    // Ссылочные поля.
    public const RECENTLY_USED = 'RECENTLY_USED';

    /**
     * Метод возвращает идентификатор объекта, для которого запрашиваются пользовательские поля.
     * Данный объект нужен для общих пользовательских полей.
     */
    public static function getUfId(): string
    {
        return static::UF_ENTITY_ID;
    }

    /**
     * {@inheritDoc}
     *
     * @return Field[]|array[]
     *
     * @throws ArgumentException
     * @throws SystemException
     *
     * @psalm-return array<non-empty-string|int, Field|array<non-empty-string, mixed>>
     */
    public static function getMap(): array
    {
        return array_merge(parent::getMap(), [
            self::PARENT => new Reference(
                self::PARENT,
                self::class,
                Join::on('this.' . self::PARENT_ID, 'ref.' . self::ID)
            ),
            self::PATH_CHILD => (
                new Reference(
                    self::PATH_CHILD,
                    ObjectPathTable::class,
                    Join::on('this.' . self::ID, 'ref.' . ObjectPathTable::OBJECT_ID)
                )
            )->configureJoinType(JoinType::INNER),
        ]);
    }
}
