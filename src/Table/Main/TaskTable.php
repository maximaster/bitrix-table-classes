<?php

declare(strict_types=1);

namespace Maximaster\BitrixTableClasses\Table\Main;

use Bitrix\Main\DB\SqlExpression;
use Bitrix\Main\ORM\Fields\ExpressionField;
use Bitrix\Main\ORM\Fields\Field;
use Bitrix\Main\ORM\Query\Query;
use Bitrix\Main\SystemException;
use Maximaster\BitrixTableClasses\Mixins\Dmbat;
use Maximaster\BitrixTableClasses\Mixins\TableMapMergerTrait;
use Maximaster\BitrixTableFields\Field\ListFromSubsequenceField;

/**
 * Переопределяем базовый класс ObjectTable модуля "Disk".
 * Переопределение делается для того, чтобы предоставить возможность создвать
 * общие пользовательские поля для объекта.
 */
class TaskTable extends \Bitrix\Main\TaskTable
{
    use TableMapMergerTrait;
    use Dmbat;

    public const ID = 'ID';
    public const NAME = 'NAME';
    public const LETTER = 'LETTER';
    public const MODULE_ID = 'MODULE_ID';
    public const SYS = 'SYS';
    public const DESCRIPTION = 'DESCRIPTION';
    public const BINDING = 'BINDING';
    public const OPERATIONS_NAMES = 'OPERATIONS_NAMES';

    /** @var string */
    public const UF_ENTITY_ID = 'TASK';

    /**
     * Метод возвращает идентификатор объекта, для которого запрашиваются пользовательские поля.
     * Данный объект нужен для общих пользовательских полей.
     */
    public static function getUfId(): string
    {
        return static::UF_ENTITY_ID;
    }

    /**
     * @throws SystemException
     *
     * @psalm-return array<string|int, Field|array<non-empty-string, mixed>>
     *
     * @noinspection PhpMultipleClassDeclarationsInspection
     */
    public static function getMap(): array
    {
        return TableMapMergerTrait::mergeMaps(parent::getMap(), [
            self::OPERATIONS_NAMES => (new ExpressionField(
                self::OPERATIONS_NAMES,
                '(' . (new Query(TaskOperationTable::getEntity()))
                    ->registerRuntimeField(
                        null,
                        new ExpressionField('names', 'GROUP_CONCAT(%s)', TaskOperationTable::OPERATION_NAME)
                    )
                    ->addSelect('names')
                    ->addFilter(TaskOperationTable::TASK_ID, new SqlExpression('%s'))
                    ->getQuery() . ')',
                [self::ID]
            ))->configureValueType(ListFromSubsequenceField::class),
        ]);
    }
}
