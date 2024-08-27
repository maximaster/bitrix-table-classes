<?php

declare(strict_types=1);

namespace Maximaster\BitrixTableClasses\Table\Disk;

use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields\DatetimeField;
use Bitrix\Main\ORM\Fields\EnumField;
use Bitrix\Main\ORM\Fields\Field;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\ORM\Fields\Validators\LengthValidator;
use Bitrix\Main\ORM\Query\Join;
use Bitrix\Main\Type\DateTime;
use Maximaster\BitrixEnums\Main\Orm\JoinType;

/**
 * Таблица блокировок объектов диска. Определяется с нуля, т.к. у Битрикса это final.
 */
class ObjectLockTable extends DataManager
{
    public const TYPE_WRITE = 2;
    public const TYPE_READ = 3;

    public const ID = 'ID';
    public const TOKEN = 'TOKEN';
    public const OBJECT_ID = 'OBJECT_ID';
    public const OBJECT = 'OBJECT';
    public const CREATED_BY = 'CREATED_BY';
    public const CREATE_TIME = 'CREATE_TIME';
    public const EXPIRY_TIME = 'EXPIRY_TIME';
    public const TYPE = 'TYPE';
    public const IS_EXCLUSIVE = 'IS_EXCLUSIVE';

    public static function getTableName(): string
    {
        return 'b_disk_object_lock';
    }

    /**
     * @psalm-return array<string|int, Field|array<non-empty-string, mixed>>
     */
    public static function getMap(): array
    {
        return [
            self::ID => (new IntegerField(self::ID))
                ->configureAutocomplete(true)
                ->configurePrimary(true),
            self::TOKEN => (new StringField(self::TOKEN))
                ->configureRequired(true)
                ->addValidator(new LengthValidator(null, 255)),
            self::OBJECT_ID => (new IntegerField(self::OBJECT_ID))
                ->configureRequired(true),
            self::OBJECT => (new Reference(
                self::OBJECT_ID,
                ObjectTable::class,
                Join::on('this.' . self::OBJECT_ID, 'ref.' . ObjectTable::ID)
            ))
                ->configureJoinType(JoinType::INNER),
            self::CREATED_BY => (new IntegerField(self::CREATED_BY))
                ->configureRequired(true),
            self::CREATE_TIME => (new DatetimeField(self::CREATE_TIME))
                ->configureRequired(true)
                ->configureDefaultValue(static fn () => new DateTime()),
            self::EXPIRY_TIME => (new DatetimeField(self::EXPIRY_TIME)),
            self::TYPE => (new EnumField(self::TYPE))
                ->configureValues(static::getListOfTypeValues())
                ->configureDefaultValue(self::TYPE_WRITE)
                ->configureRequired(true),
            self::IS_EXCLUSIVE => (new IntegerField(self::IS_EXCLUSIVE))
                ->configureDefaultValue(1),
        ];
    }

    /**
     * @return int[]
     */
    public static function getListOfTypeValues(): array
    {
        return [self::TYPE_READ, self::TYPE_WRITE];
    }
}
