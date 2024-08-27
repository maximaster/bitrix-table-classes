<?php

declare(strict_types=1);

namespace Maximaster\BitrixTableClasses\Table\Main;

use Bitrix\Main\ArgumentException;
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Entity;
use Bitrix\Main\ORM\Fields\Field;
use Bitrix\Main\ORM\Fields\ScalarField;
use Bitrix\Main\ORM\Fields\TextField;
use Bitrix\Main\SystemException;
use Exception as PhpException;
use Maximaster\BitrixEnums\Main\Orm\JoinType;
use Maximaster\BitrixEnums\Main\Truth;
use Maximaster\BitrixTableFields\Field\PositiveIntegerField;
use Maximaster\BitrixTableFields\Field\ReferenceField;

/**
 * Базовая таблица для полей с единственным значением.
 */
abstract class UtsTable extends DataManager
{
    public const VALUE_ID = 'VALUE_ID';
    public const PARENT = 'PARENT';

    /**
     * Класс связанной таблицы.
     *
     * @psalm-return class-string<DataManager>
     */
    abstract public static function relationTableClass(): string;

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
            self::VALUE_ID => PositiveIntegerField::required(self::VALUE_ID)
                ->configurePrimary(true),
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

        $userFields = $USER_FIELD_MANAGER->getUserFields($relationTableClass::getUfId());

        foreach ($userFields as $userField) {
            if ($userField[UserFieldTable::MULTIPLE] === Truth::YES) {
                continue;
            }

            $field = $USER_FIELD_MANAGER->getEntityField(
                $userField,
                null,
                is_array($userField[UserFieldTable::SETTINGS]) === true ? $userField[UserFieldTable::SETTINGS] : []
            );
            $entity->addField($field);

            // TODO Временный костыль, так как $USER_FIELD_MANAGER->getEntityReferences
            //      требует вторым параметром ScalarField, но поля могут быть и не наследниками
            //      ScalarField. Подумать над решением проблемы.
            if (is_a($field, ScalarField::class, false) === true) {
                foreach ($USER_FIELD_MANAGER->getEntityReferences($userField, $field) as $reference) {
                    $entity->addField($reference);
                }
            }
        }

        // TODO Пока оставлен для совместимости.
        foreach ($userFields as $userField) {
            if ($userField[UserFieldTable::MULTIPLE] === Truth::NO) {
                continue;
            }

            // add serialized utm cache-fields
            $cacheField = new TextField($userField['FIELD_NAME']);
            UserFieldTable::setMultipleFieldSerialization($cacheField, $userField);
            $entity->addField($cacheField);
        }
    }
}
