<?php

declare(strict_types=1);

namespace Maximaster\BitrixTableClasses\Table\Main;

use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields\ExpressionField;
use Bitrix\Main\ORM\Fields\Field;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;
use Maximaster\BitrixTableClasses\Mixins\Dmbat;

/**
 * Описание таблицы, хранящей перечень операций уровней доступа.
 */
class TaskOperationTable extends DataManager
{
    use Dmbat;

    public const TASK_ID = 'TASK_ID';
    public const OPERATION_ID = 'OPERATION_ID';
    public const OPERATION = 'OPERATION';
    public const OPERATION_NAME = 'OPERATION_NAME';

    public static function getTableName(): string
    {
        return 'b_task_operation';
    }

    /**
     * @psalm-return array<string|int, Field|array<non-empty-string, mixed>>
     */
    public static function getMap(): array
    {
        return [
            self::TASK_ID => (
                new IntegerField(self::TASK_ID)
            )
                ->configurePrimary(true),
            self::OPERATION_ID => (
                new IntegerField(self::OPERATION_ID)
            )
                ->configurePrimary(true),
            self::OPERATION => (new Reference(
                self::OPERATION,
                OperationTable::class,
                Join::on('this.' . self::OPERATION_ID, 'ref.' . OperationTable::ID)
            )),
            self::OPERATION_NAME => new ExpressionField(
                self::OPERATION_NAME,
                '%s',
                self::OPERATION . '.' . OperationTable::NAME
            ),
        ];
    }
}
