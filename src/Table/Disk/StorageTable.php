<?php

declare(strict_types=1);

namespace Maximaster\BitrixTableClasses\Table\Disk;

use Bitrix\Disk\Internals\DataManager;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\ArgumentTypeException;
use Bitrix\Main\ORM\Fields\BooleanField;
use Bitrix\Main\ORM\Fields\Field;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\ORM\Fields\TextField;
use Bitrix\Main\ORM\Fields\Validators;
use Bitrix\Main\ORM\Query\Join;
use Bitrix\Main\SystemException;
use Maximaster\BitrixEnums\Main\Orm\JoinType;
use Maximaster\BitrixTableClasses\Mixins\Dmbat;
use Maximaster\BitrixTableClasses\Mixins\TableMapMergerTrait;

/**
 * Переопределяем базовый класс StorageTable модуля "Disk".
 * Переопределение делается для того, чтобы предоставить возможность создвать общие пользовательские поля для хранилища.
 * Базовый класс StorageTable запрещает переопределение в дочерних классах (является final).
 */
class StorageTable extends DataManager
{
    use TableMapMergerTrait;
    use Dmbat;

    /** @var string */
    public const UF_ENTITY_ID = 'DISK_STORAGE';
    public const ID = 'ID';
    public const NAME = 'NAME';
    public const CODE = 'CODE';
    public const XML_ID = 'XML_ID';
    public const MODULE_ID = 'MODULE_ID';
    public const ENTITY_TYPE = 'ENTITY_TYPE';
    public const ENTITY_ID = 'ENTITY_ID';
    public const ENTITY_MISC_DATA = 'ENTITY_MISC_DATA';
    public const ROOT_OBJECT_ID = 'ROOT_OBJECT_ID';
    public const ROOT_OBJECT = 'ROOT_OBJECT';
    public const USE_INTERNAL_RIGHTS = 'USE_INTERNAL_RIGHTS';
    public const SITE_ID = 'SITE_ID';
    public const ROOT_OBJECT_RIGHT = 'ROOT_OBJECT_RIGHT';

    /**
     * Метод возвращает идентификатор объекта, для которого запрашиваются пользовательские поля.
     * Данный объект нужен для общих пользовательских полей.
     */
    public static function getUfId(): string
    {
        return static::UF_ENTITY_ID;
    }

    public static function getTableName(): string
    {
        return 'b_disk_storage';
    }

    /**
     * {@inheritDoc}
     *
     * @return Field[]
     *
     * @throws ArgumentException
     * @throws ArgumentTypeException
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
            self::NAME => (
                new StringField(self::NAME)
            )
                ->addValidator(new Validators\LengthValidator(null, 100)),
            self::CODE => (
                new StringField(self::CODE)
            )
                ->addValidator(new Validators\LengthValidator(null, 32)),
            self::XML_ID => (
                new StringField(self::XML_ID)
            )
                ->addValidator(new Validators\LengthValidator(null, 50)),
            self::MODULE_ID => (
                new StringField(self::MODULE_ID)
            )
                ->configureRequired(true)
                ->addValidator(new Validators\LengthValidator(1, 32))
                ->addValidator(new Validators\RegExpValidator('/^[a-zA-Z0-9_-]+$/')),
            self::ENTITY_TYPE => (
                new StringField(self::ENTITY_TYPE)
            )
                ->configureRequired(true)
                ->addValidator(new Validators\LengthValidator(1, 100)),
            self::ENTITY_ID => (
                new StringField(self::ENTITY_ID)
            )
                ->configureRequired(true)
                ->addValidator(new Validators\LengthValidator(1, 32))
                ->addValidator(new Validators\RegExpValidator('/^[a-zA-Z0-9_-]+$/')),
            self::ENTITY_MISC_DATA => new TextField(self::ENTITY_MISC_DATA),
            self::ROOT_OBJECT_ID => new IntegerField(self::ROOT_OBJECT_ID),
            self::ROOT_OBJECT => (
                new Reference(
                    self::ROOT_OBJECT,
                    ObjectTable::class,
                    Join::on('this.' . self::ROOT_OBJECT_ID, 'ref.ID')
                )
            )
                ->configureJoinType('inner'),
            self::USE_INTERNAL_RIGHTS => (
                new BooleanField(self::USE_INTERNAL_RIGHTS)
            )
                ->configureDefaultValue(1)
                ->configureValues(0, 1),
            self::SITE_ID => (
                new StringField(self::SITE_ID)
            )
                ->addValidator(new Validators\LengthValidator(null, 2)),
            self::ROOT_OBJECT_RIGHT => (new Reference(
                self::ROOT_OBJECT_RIGHT,
                RightTable::class,
                Join::on('this.' . self::ROOT_OBJECT_ID, 'ref.' . RightTable::OBJECT_ID)
            ))->configureJoinType(JoinType::INNER),
        ];
    }
}
