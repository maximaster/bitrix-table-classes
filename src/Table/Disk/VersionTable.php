<?php

declare(strict_types=1);

namespace Maximaster\BitrixTableClasses\Table\Disk;

use Bitrix\Disk\Internals\DataManager;
use Bitrix\Disk\Internals\FileTable;
use Bitrix\Disk\Internals\ObjectPathTable;
use Bitrix\Main\Entity;
use Bitrix\Main\ORM\Fields\DatetimeField;
use Bitrix\Main\ORM\Fields\Field;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\ORM\Fields\TextField;
use Bitrix\Main\ORM\Query\Join;
use Bitrix\Main\SystemException;
use Bitrix\Main\Type\DateTime;
use Maximaster\BitrixTableClasses\Table\Main\UserTable;
use Maximaster\BitrixTableClasses\Mixins\Dmbat;

/**
 * Переопределяем базовый класс VersionTable модуля "Disk".
 *
 * Базовый класс VersionTable запрещает переопределение в дочерних классах (является final).
 */
class VersionTable extends DataManager
{
    use Dmbat;

    public const ID = 'ID';
    public const OBJECT_ID = 'OBJECT_ID';
    public const OBJECT = 'OBJECT';
    public const SIZE = 'SIZE';
    public const FILE_ID = 'FILE_ID';
    public const NAME = 'NAME';
    public const CREATE_TIME = 'CREATE_TIME';
    public const CREATED_BY = 'CREATED_BY';
    public const CREATE_USER = 'CREATE_USER';
    public const PATH_PARENT = 'PATH_PARENT';
    public const PATH_CHILD = 'PATH_CHILD';
    public const OBJECT_CREATE_TIME = 'OBJECT_CREATE_TIME';
    public const OBJECT_CREATED_BY = 'OBJECT_CREATED_BY';
    public const OBJECT_UPDATE_TIME = 'OBJECT_UPDATE_TIME';
    public const OBJECT_UPDATED_BY = 'OBJECT_UPDATED_BY';
    public const GLOBAL_CONTENT_VERSION = 'GLOBAL_CONTENT_VERSION';
    public const MISC_DATA = 'MISC_DATA';
    public const VIEW_ID = 'VIEW_ID';

    public const UF_ENTITY_ID = 'DISK_FILE_VERSION';

    /**
     * Возвращает идентификатор объекта, для которого запрашиваются пользовательские поля.
     * Нужен для общих пользовательских полей.
     */
    public static function getUfId(): string
    {
        return static::UF_ENTITY_ID;
    }

    public static function getTableName(): string
    {
        return 'b_disk_version';
    }

    /**
     * {@inheritDoc}
     *
     * @return Field[]
     *
     * @throws SystemException
     *
     * @psalm-return non-empty-array<non-empty-string, Field>
     */
    public static function getMap(): array
    {
        return [
            self::ID => (
                new IntegerField(self::ID)
            )
                ->configureAutocomplete(true)
                ->configurePrimary(true),
            self::OBJECT_ID => (
                new IntegerField(self::OBJECT_ID)
            )
                ->configureRequired(true),
            self::OBJECT => (
                new Reference(
                    self::OBJECT,
                    FileTable::class,
                    Join::on('this.' . self::OBJECT_ID, 'ref.ID')
                )
            ),
            self::SIZE => (
                new IntegerField(self::SIZE)
            )
                ->configureRequired(true),
            self::FILE_ID => (
                new IntegerField(self::FILE_ID)
            )
                ->configureRequired(true),
            self::NAME => (
                new StringField(self::NAME)
            )
                ->addValidator(new Entity\Validator\Length(1, 255)),
            self::CREATE_TIME => (
                new DatetimeField(self::CREATE_TIME)
            )
                ->configureRequired(true)
                ->configureDefaultValue(function (): DateTime {
                    return new DateTime();
                }),
            self::CREATED_BY => new IntegerField(self::CREATED_BY),
            self::CREATE_USER => (
                new Reference(
                    self::CREATE_USER,
                    UserTable::class,
                    Join::on('this.' . self::CREATED_BY, 'ref.' . UserTable::ID)
                )
            ),
            self::PATH_PARENT => (
                new Reference(
                    self::PATH_PARENT,
                    ObjectPathTable::class,
                    Join::on('this.' . self::OBJECT_ID, 'ref.PARENT_ID')
                )
            )
                ->configureJoinType('inner'),
            self::PATH_CHILD => (
                new Reference(
                    self::PATH_CHILD,
                    ObjectPathTable::class,
                    Join::on('this.' . self::OBJECT_ID, 'ref.OBJECT_ID')
                )
            )
                ->configureJoinType('inner'),
            self::OBJECT_CREATE_TIME => new DatetimeField(self::OBJECT_CREATE_TIME),
            self::OBJECT_CREATED_BY => new IntegerField(self::OBJECT_CREATED_BY),
            self::OBJECT_UPDATE_TIME => new DatetimeField(self::OBJECT_UPDATE_TIME),
            self::OBJECT_UPDATED_BY => new IntegerField(self::OBJECT_UPDATED_BY),
            self::GLOBAL_CONTENT_VERSION => new IntegerField(self::GLOBAL_CONTENT_VERSION),
            self::MISC_DATA => new TextField(self::MISC_DATA),
            self::VIEW_ID => new IntegerField(self::VIEW_ID),
        ];
    }
}
