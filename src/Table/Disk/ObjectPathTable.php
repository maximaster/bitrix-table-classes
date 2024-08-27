<?php

declare(strict_types=1);

namespace Maximaster\BitrixTableClasses\Table\Disk;

use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields\ExpressionField;
use Bitrix\Main\ORM\Fields\Field;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\ORM\Query\Join;
use Maximaster\BitrixTableFields\Field\PrimaryIntegerField;
use Maximaster\BitrixTableClasses\Mixins\Dmbat;

/**
 * Таблица хранящая информацию о пути к файлу.
 *
 * @see \Bitrix\Disk\Internals\ObjectPathTable
 */
class ObjectPathTable extends DataManager
{
    use Dmbat;

    // Поля
    public const ID = 'ID';
    public const PARENT_ID = 'PARENT_ID';
    public const OBJECT_ID = 'OBJECT_ID';
    public const DEPTH_LEVEL = 'DEPTH_LEVEL';
    // Ссылки
    public const PARENT = 'PARENT';
    public const OBJECT = 'OBJECT';
    // Вычисляемые
    public const PATH = 'PATH';
    public const PARENT_XML_ID = 'PARENT_XML_ID';
    public const OBJECT_XML_ID = 'OBJECT_XML_ID';

    public static function getTableName(): string
    {
        return 'b_disk_object_path';
    }

    /**
     * @psalm-return array<string|int, Field|array<non-empty-string, mixed>>
     */
    public static function getMap(): array
    {
        return [
            // Поля
            self::ID => PrimaryIntegerField::on(self::ID),
            self::PARENT_ID => (new IntegerField(self::PARENT_ID))->configureRequired(true),
            self::OBJECT_ID => (new IntegerField(self::OBJECT_ID))->configureRequired(true),
            self::DEPTH_LEVEL => (new IntegerField(self::DEPTH_LEVEL))->configureRequired(true),
            // Ссылки
            self::PARENT => (new Reference(
                self::PARENT,
                ObjectTable::class,
                Join::on('this.' . self::PARENT_ID, 'ref.' . ObjectTable::ID)
            )),
            self::OBJECT => (new Reference(
                self::OBJECT,
                ObjectTable::class,
                Join::on('this.' . self::OBJECT_ID, 'ref.' . ObjectTable::ID)
            )),
            self::PATH => (new ExpressionField(
                'PATH',
                'GROUP_CONCAT(%s ORDER BY %s DESC SEPARATOR "/")',
                [self::PARENT . '.' . ObjectTable::NAME, self::DEPTH_LEVEL]
            ))
                ->configureValueType(StringField::class),
            self::PARENT_XML_ID => new ExpressionField(
                self::PARENT_XML_ID,
                '%s',
                self::PARENT . '.' . ObjectTable::XML_ID
            ),
            self::OBJECT_XML_ID => new ExpressionField(
                self::OBJECT_XML_ID,
                '%s',
                self::OBJECT . '.' . ObjectTable::XML_ID
            ),
        ];
    }
}
