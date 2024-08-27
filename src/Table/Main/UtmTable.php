<?php

declare(strict_types=1);

namespace Maximaster\BitrixTableClasses\Table\Main;

use Bitrix\Main\ArgumentException;
use Bitrix\Main\DB\SqlExpression;
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Entity;
use Bitrix\Main\ORM\Fields\DateField;
use Bitrix\Main\ORM\Fields\Field;
use Bitrix\Main\ORM\Fields\FloatField;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\ScalarField;
use Bitrix\Main\ORM\Fields\TextField;
use Bitrix\Main\ORM\Query\Filter\Condition;
use Bitrix\Main\SystemException;
use Exception as PhpException;
use Maximaster\BitrixEnums\Main\Orm\JoinType;
use Maximaster\BitrixEnums\Main\Truth;
use Maximaster\BitrixTableFields\Field\DateTimeImmutableField;
use Maximaster\BitrixTableFields\Field\PositiveIntegerField;
use Maximaster\BitrixTableFields\Field\PrimaryIntegerField;
use Maximaster\BitrixTableFields\Field\ReferenceField;

/**
 * Базовая таблица для полей с множественным значением.
 */
abstract class UtmTable extends DataManager
{
    public const ID = 'ID';
    public const VALUE_ID = 'VALUE_ID';
    public const PARENT = 'PARENT';
    public const FIELD_ID = 'FIELD_ID';
    public const VALUE = 'VALUE';
    public const VALUE_INT = 'VALUE_INT';
    public const VALUE_DOUBLE = 'VALUE_DOUBLE';
    public const VALUE_DATE = 'VALUE_DATE';

    public const SINGLE_COLUMN_POSTFIX = '_SINGLE';

    /**
     * Класс связанной таблицы.
     *
     * @psalm-return class-string<DataManager>
     */
    abstract protected static function relationTableClass(): string;

    /**
     * {@inheritDoc}
     *
     * @throws SystemException
     *
     * @psalm-return array<non-empty-string, Field>
     */
    public static function getMap(): array
    {
        return [
            self::ID => PrimaryIntegerField::on(self::ID),
            self::VALUE_ID => PositiveIntegerField::required(self::VALUE_ID)
                ->configurePrimary(true),
            self::FIELD_ID => PositiveIntegerField::required(self::FIELD_ID),
            self::VALUE => new TextField(self::VALUE),
            self::VALUE_INT => new IntegerField(self::VALUE_INT),
            self::VALUE_DOUBLE => new FloatField(self::VALUE_DOUBLE),
            self::VALUE_DATE => DateTimeImmutableField::on(self::VALUE_DATE),
        ];
    }

    /**
     * @throws ArgumentException
     * @throws PhpException
     * @throws SystemException
     *
     * @SuppressWarnings(PHPMD.CamelCaseVariableName)
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     *
     * @phpstan-ignore-next-line dirty parent
     */
    public static function postInitialize(Entity $entity): void
    {
        // TODO Заменить на CUserTypeManager после того как починиться EventDispatcher.
        global $USER_FIELD_MANAGER;

        /** @psalm-var class-string<DataManager> $relationTableClass */
        $relationTableClass = static::relationTableClass();

        if (class_exists($relationTableClass) === false) {
            throw new PhpException('Не определен класс связанной таблицы.');
        }

        $baseFields = [
            self::PARENT => ReferenceField::for(
                self::PARENT,
                $relationTableClass,
                self::VALUE_ID,
                'ID',
                JoinType::INNER()
            ),
        ];

        foreach ($baseFields as $baseField) {
            $entity->addField($baseField);
        }

        $utmFields = $USER_FIELD_MANAGER->getUserFields($relationTableClass::getUfId());

        foreach ($utmFields as $utmField) {
            if ($utmField[UserFieldTable::MULTIPLE] === Truth::NO) {
                continue;
            }

            $field = $USER_FIELD_MANAGER->getEntityField($utmField);
            $columnName = 'VALUE';

            if ($field instanceof IntegerField) {
                $columnName = 'VALUE_INT';
            }

            if ($field instanceof FloatField) {
                $columnName = 'VALUE_DOUBLE';
            }

            if ($field instanceof DateField) {
                $columnName = 'VALUE_DATE';
            }

            $field->setColumnName($columnName);
            $entity->addField($field);

            // TODO Временный костыль, так как $USER_FIELD_MANAGER->getEntityReferences
            //      требует вторым параметром ScalarField, но поля могут быть и не наследниками
            //      ScalarField. Подумать над решением проблемы.
            if (is_a($field, ScalarField::class, false) === true) {
                foreach ($USER_FIELD_MANAGER->getEntityReferences($utmField, $field) as $reference) {
                    $entity->addField($reference);
                }
            }

            $refField = ReferenceField::for(
                'PARENT_' . $utmField[UserFieldTable::FIELD_NAME],
                $relationTableClass,
                self::VALUE_ID,
                'ID',
                JoinType::LEFT(),
                new Condition(
                    'this.' . self::FIELD_ID,
                    '=',
                    new SqlExpression('?i', $utmField[UserFieldTable::ID])
                )
            );

            $entity->addField($refField);
        }
    }
}
