<?php

declare(strict_types=1);

namespace Maximaster\BitrixTableClasses\Table\Iblock;

use Bitrix\Iblock\ElementTable as BitrixElementTable;
use Bitrix\Main\ORM\Fields\ExpressionField;
use Bitrix\Main\ORM\Fields\Field;
use Bitrix\Main\SystemException;
use Maximaster\BitrixTableClasses\Mixins\DataManagerForwarderTrait;
use Maximaster\BitrixTableClasses\Mixins\Dmbat;
use Maximaster\BitrixTableClasses\Mixins\TableMapMergerTrait;
use Maximaster\BitrixTableClasses\Table\Main\UtmTable;
use Maximaster\BitrixTableClasses\Table\Main\UtsTable;

class ElementTable extends BitrixElementTable
{
    use IblockTableTrait;
    use DataManagerForwarderTrait;
    use Dmbat;
    use TableMapMergerTrait;

    // Поля таблицы
    public const ID = 'ID';
    public const TIMESTAMP_X = 'TIMESTAMP_X';
    public const MODIFIED_BY = 'MODIFIED_BY';
    public const DATE_CREATE = 'DATE_CREATE';
    public const CREATED_BY = 'CREATED_BY';
    public const IBLOCK_ID = 'IBLOCK_ID';
    public const IBLOCK_SECTION_ID = 'IBLOCK_SECTION_ID';
    public const ACTIVE = 'ACTIVE';
    public const ACTIVE_FROM = 'ACTIVE_FROM';
    public const ACTIVE_TO = 'ACTIVE_TO';
    public const SORT = 'SORT';
    public const NAME = 'NAME';
    public const PREVIEW_PICTURE = 'PREVIEW_PICTURE';
    public const PREVIEW_TEXT = 'PREVIEW_TEXT';
    public const PREVIEW_TEXT_TYPE = 'PREVIEW_TEXT_TYPE';
    public const DETAIL_PICTURE = 'DETAIL_PICTURE';
    public const DETAIL_TEXT = 'DETAIL_TEXT';
    public const DETAIL_TEXT_TYPE = 'DETAIL_TEXT_TYPE';
    public const SEARCHABLE_CONTENT = 'SEARCHABLE_CONTENT';
    public const WF_STATUS_ID = 'WF_STATUS_ID';
    public const WF_PARENT_ELEMENT_ID = 'WF_PARENT_ELEMENT_ID';
    public const WF_NEW = 'WF_NEW';
    public const WF_LOCKED_BY = 'WF_LOCKED_BY';
    public const WF_DATE_LOCK = 'WF_DATE_LOCK';
    public const WF_COMMENTS = 'WF_COMMENTS';
    public const IN_SECTIONS = 'IN_SECTIONS';
    public const XML_ID = 'XML_ID';
    public const CODE = 'CODE';
    public const TAGS = 'TAGS';
    public const TMP_ID = 'TMP_ID';
    public const WF_LAST_HISTORY_ID = 'WF_LAST_HISTORY_ID';
    public const SHOW_COUNTER = 'SHOW_COUNTER';
    public const SHOW_COUNTER_START = 'SHOW_COUNTER_START';

    // Виртуальные и ссылочные поля
    public const IBLOCK = 'IBLOCK';
    public const WF_PARENT_ELEMENT = 'WF_PARENT_ELEMENT';
    public const IBLOCK_SECTION = 'IBLOCK_SECTION';
    public const MODIFIED_BY_USER = 'MODIFIED_BY_USER';
    public const CREATED_BY_USER = 'CREATED_BY_USER';
    public const WF_LOCKED_BY_USER = 'WF_LOCKED_BY_USER';
    public const UTS_OBJECT = 'UTS_OBJECT';
    public const IBLOCK_TYPE = 'IBLOCK_TYPE';
    public const IBLOCK_XML_ID = 'IBLOCK_XML_ID';

    public static function getUfId(): ?string
    {
        $iblockId = static::getIblockId();

        return $iblockId === null ? null : "IBLOCK_{$iblockId}_ELEMENT";
    }

    public static function buildObjectClass(string $entityClass, int $iblockId): string
    {
        return sprintf('%s\\Iblock%dEntity', $entityClass, $iblockId);
    }

    /**
     * Сгенерировать новый ElementTable привязанный к определённому инфоблок и EntityObject.
     *
     * @return static|string
     *
     * @psalm-return class-string<static>
     *
     * @SuppressWarnings(PHPMD.EvalExpression) why:dependency
     */
    public static function bind(int $iblockId, ?string $basicEntityClass = null, ?string $namespace = null): string
    {
        if ($namespace === null) {
            $namespace = static::class;
        }

        $tableClassShortName = sprintf('Iblock%dTable', $iblockId);
        $tableClassName = sprintf('%s\\%s', $namespace, $tableClassShortName);

        if (class_exists($tableClassName, false) === false) {
            $entityClass = null;
            if ($basicEntityClass !== null) {
                $entityNamespace = $basicEntityClass;
                $entityShortname = sprintf('Iblock%dEntity', $iblockId);
                $entityClass = sprintf('%s\\%s', $entityNamespace, $entityShortname);

                eval(sprintf(
                    '
                    namespace %s;

                    class %s extends \\%s
                    {
                        public static $dataClass = \\%s::class;
                    }
                ',
                    $entityNamespace,
                    $entityShortname,
                    $basicEntityClass,
                    $tableClassName
                ));
            }

            eval(sprintf(
                '
                namespace %s;

                class %s extends \\%s
                {
                    public static function getIblockId(): int
                    {
                        return %d;
                    }

                    %s
                }
            ',
                $namespace,
                $tableClassShortName,
                static::class,
                $iblockId,
                $entityClass === null
                    ? ''
                    : sprintf(
                        '
                        public static function getObjectClass(): string
                        {
                            return \\%s::class;
                        }
                    ',
                        $entityClass
                    )
            ));
        }

        /** @psalm-var class-string<static> $tableClassName */
        return $tableClassName;
    }

    /**
     * Сгенерировать новый класс UtsTable, привязанный к определённому инфоблоку.
     *
     * @return UtsTable|string
     *
     * @psalm-return class-string<UtsTable>
     *
     * @SuppressWarnings(PHPMD.EvalExpression) why:dependency
     */
    public static function bindFieldValueUtsTable(?string $namespace = null): string
    {
        if ($namespace === null) {
            $namespace = static::class;
        }

        $lowerUf = strtolower(static::getUfId());

        $tableClassShortName = implode('', array_map(
            static fn ($item) => ucfirst($item),
            explode('_', $lowerUf)
        )) . 'UtsTable';
        $tableClassName = sprintf('%s\\%s', $namespace, $tableClassShortName);
        $tableName = 'b_uts_' . $lowerUf;

        if (class_exists($tableClassName, false) === false) {
            eval(sprintf(
                '
                namespace %s;

                class %s extends \\%s
                {
                    public static function relationTableClass(): string
                    {
                        return \\%s::class;
                    }

                    public static function getTableName(): string
                    {
                        return %s;
                    }
                }
            ',
                $namespace,
                $tableClassShortName,
                UtsTable::class,
                static::class,
                $tableName
            ));
        }

        /** @psalm-var class-string<UtsTable> $tableClassName */
        return $tableClassName;
    }

    /**
     * Сгенерировать новый класс UtmTable, привязанный к определённому инфоблоку.
     *
     * @return UtmTable|string
     *
     * @psalm-return class-string<UtmTable>
     *
     * @SuppressWarnings(PHPMD.EvalExpression) why:intended
     */
    public static function bindFieldValueUtmTable(?string $namespace = null): string
    {
        if ($namespace === null) {
            $namespace = static::class;
        }

        $lowerUf = strtolower(static::getUfId());

        $tableClassShortName = implode('', array_map(
            static fn ($item) => ucfirst($item),
            explode('_', $lowerUf)
        )) . 'UtmTable';
        $tableClassName = sprintf('%s\\%s', $namespace, $tableClassShortName);
        $tableName = 'b_utm_' . $lowerUf;

        if (class_exists($tableClassName, false) === false) {
            eval(sprintf(
                '
                namespace %s;

                class %s extends \\%s
                {
                    public static function relationTableClass(): string
                    {
                        return \\%s::class;
                    }

                    public static function getTableName(): string
                    {
                        return %s;
                    }
                }
            ',
                $namespace,
                $tableClassShortName,
                UtmTable::class,
                static::class,
                $tableName
            ));
        }

        /** @psalm-var class-string<UtmTable> $tableClassName */
        return $tableClassName;
    }

    /**
     * @throws SystemException
     *
     * @psalm-return array<string|int, Field|array<non-empty-string, mixed>>
     */
    public static function getMap(): array
    {
        return array_merge(parent::getMap(), [
            self::IBLOCK_TYPE => new ExpressionField(
                self::IBLOCK_TYPE,
                '%s',
                self::IBLOCK . '.' . IBlockTable::IBLOCK_TYPE_ID
            ),
            self::IBLOCK_XML_ID => new ExpressionField(
                self::IBLOCK_XML_ID,
                '%s',
                self::IBLOCK . '.' . IBlockTable::XML_ID
            ),
        ]);
    }
}
