<?php

declare(strict_types=1);

namespace Maximaster\BitrixTableClasses\Mixins;

use Bitrix\Main\ArgumentException;
use Bitrix\Main\NotImplementedException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\ORM\Entity;
use Bitrix\Main\ORM\Fields\Field;
use Bitrix\Main\ORM\Fields\FieldTypeMask;
use Bitrix\Main\ORM\Objectify\Collection;
use Bitrix\Main\ORM\Objectify\EntityObject;
use Bitrix\Main\SystemException;
use Maximaster\BitrixEnums\Main\Orm\QueryParameter;
use Maximaster\BitrixTableClasses\Exception\EntityNotFoundException;

/**
 * Trait для DataManager ради упрощения базовых операций.
 */
trait Dmbat
{
    /**
     * Возвращает настройки полей.
     *
     * @return array
     *
     * @psalm-return array<string|int, array|Field>
     */
    abstract public static function getMap();

    /**
     * Возвращает объектные настройки данной таблицы.
     *
     * @return Entity
     */
    abstract public static function getEntity();

    /**
     * Возвращает уникальный идентификатор для таблицы UF-полей.
     *
     * @return string|null
     */
    abstract public static function getUfId();

    /**
     * Возвращает имя класса выбираемых объектов.
     */
    abstract public static function getObjectClass();

    /**
     * Возвращает имена полей. Всех или определённых типов.
     *
     * @param int $mask
     *
     * @return string[]
     */
    public static function fieldNames($mask = FieldTypeMask::ALL): array
    {
        $fields = [];
        foreach (static::getEntity()->getFields() as $field) {
            if (($mask & $field->getTypeMask()) === 1) {
                $fields[] = $field->getName();
            }
        }

        // @phpstan-ignore-next-line why:false-positive
        if (static::getUfId() !== null) {
            $fields[] = 'UF_*';
        }

        return $fields;
    }

    /**
     * Производит выборку по параметрам и получает один объект или NULL если ничего не найдено. Выбирается 1 объект.
     *
     * @param string[] $order
     *
     * @psalm-param array<int|string, mixed> $filter
     * @psalm-param array<non-empty-string, 'asc'|'desc'|'ASC'|'DESC'> $order
     */
    public static function fetchObject(array $filter, array $order = []): ?EntityObject
    {
        $result = static::getList(compact('filter') + [
            'order' => $order,
            'limit' => 1,
        ]);

        return $result->fetchObject();
    }

    /**
     * @throws EntityNotFoundException
     *
     * @psalm-param array<string,mixed> $filter
     */
    public static function getObject(array $filter): EntityObject
    {
        $object = static::fetchObject($filter);

        if ($object === null) {
            throw new EntityNotFoundException(
                sprintf(
                    'Объект [%s] по запросу [%s] не найден.',
                    static::getObjectClass(),
                    json_encode($filter, JSON_UNESCAPED_UNICODE)
                )
            );
        }

        return $object;
    }

    /**
     * @param int|string|string[]|int[] $id
     *
     * @throws EntityNotFoundException
     */
    public static function getObjectByPrimary($id): EntityObject
    {
        /** @var EntityObject|null $object */
        $object = static::getByPrimary($id)->fetchObject();

        if ($object === null) {
            throw new EntityNotFoundException(
                sprintf('Объект с идентификатором %s не найден', var_export($id, true))
            );
        }

        return $object;
    }

    /**
     * Производит выборку по параметрам и возвращает коллекцию объектов.
     *
     * @psalm-param array<non-empty-string, mixed> $parameters
     */
    public static function getCollection(array $parameters = []): Collection
    {
        return static::getList($parameters)->fetchCollection();
    }

    /**
     * Производит выборку по переданным фильтрам и возвращает коллекцию объектов.
     *
     * @psalm-param array<string, mixed> $filter
     * @psalm-param array<string, mixed> $order
     */
    public static function getFilteredCollection(array $filter, array $order = []): Collection
    {
        return static::getCollection(compact('filter', 'order'));
    }

    /**
     * Есть ли такая запись в таблице?
     *
     * @param int|int[]|string|string[] $id
     *
     * @throws ArgumentException
     * @throws SystemException
     * @throws ObjectPropertyException
     *
     * @SuppressWarnings(PHPMD.ShortVariable)
     *
     * @psalm-param list<string|int>|string|int $id
     */
    public static function hasIdentity($id): bool
    {
        return static::getByPrimary(
            $id,
            [
                'select' => static::getEntity()->getPrimaryArray(),
                'limit' => 1,
            ]
        )->getSelectedRowsCount() === 1;
    }

    /**
     * Есть ли запись по указанному фильтру?
     *
     * @params array $filter
     *
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     *
     * @psalm-param array<array-key, mixed> $filter
     */
    public static function hasRow(array $filter): bool
    {
        return static::getRow(
            [
                'select' => static::getEntity()->getPrimaryArray(),
                'filter' => $filter,
            ]
        ) !== null;
    }

    /**
     * Получает одно значение одной записи.
     *
     * @psalm-param non-empty-string $field
     * @psalm-param array<array-key, mixed> $filter
     * @psalm-param array<non-empty-string, non-empty-string> $order
     *
     * @psalm-return mixed
     */
    public static function findRowField(string $field, array $filter, array $order = [])
    {
        $row = static::getRow([
            'select' => [$field],
            'filter' => $filter,
            'order' => $order,
        ]);

        return $row === null ? null : ($row[$field] ?? null);
    }

    /**
     * Получить первичный ключ одной записи по фильтру.
     *
     * @return string|int|null
     *
     * @throws NotImplementedException
     *
     * @psalm-param array<array-key, mixed> $filter
     *
     * @psalm-return int<1, max>|non-empty-string|null
     */
    public static function findPrimary(array $filter)
    {
        $primary = static::findRowField(static::getEntity()->getPrimary(), $filter);

        if ($primary === null) {
            return null;
        }

        $isScalarPrimary = (is_int($primary) && $primary > 0) || (is_string($primary) && $primary !== '');
        if ($isScalarPrimary === false) {
            throw new NotImplementedException('Составные ключи не поддерживаются.');
        }

        return $primary;
    }

    /**
     * Получает данные из определённого поля по указанным параметрам запроса.
     *
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     *
     * @psalm-param array<non-empty-string, mixed> $parameters
     * @psalm-return list<mixed>
     */
    public static function getColumn(string $field, array $parameters): array
    {
        return array_column(
            static::getList(['select' => [$field]] + $parameters)->fetchAll(),
            $field
        );
    }

    /**
     * TODO Дубликат {@link findRowField} (разница в аргументе $group).
     *
     * Получает данные определённого поля после выборки одной записи.
     *
     * @psalm-param non-empty-string $field
     * @psalm-param array<array-key, mixed> $filter
     * @psalm-param array<non-empty-string, 'asc'|'desc'> $order
     * @psalm-param list<non-empty-string> $group
     *
     * @psalm-return mixed
     */
    public static function getFirstCell(string $field, array $filter, array $order = [], array $group = [])
    {
        $row = static::getList(
            ['select' => [$field], 'limit' => 1] + array_filter(compact('filter', 'order', 'group'))
        )->fetch();

        return $row[$field] ?? null;
    }

    /**
     * Возвращает данные из определённого поля, при этом ключём массива является другое поле.
     *
     * @return mixed[]
     *
     * @psalm-param array<non-empty-string, array<array-key, mixed>> $parameters
     */
    public static function getIndexedColumn(string $keyField, string $valueField, array $parameters): array
    {
        $map = [];
        $res = static::getList(['select' => [$keyField, $valueField]] + $parameters);
        while ($row = $res->fetch()) {
            $map[$row[$keyField]] = $row[$valueField];
        }

        return $map;
    }

    /**
     * Возвращает количество найденных записей без учета limit.
     *
     * @psalm-param array<string, mixed> $filter
     */
    public static function count(array $filter): int
    {
        return static::getList(['count_total' => true] + compact('filter'))->getCount();
    }
}
