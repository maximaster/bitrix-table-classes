<?php

declare(strict_types=1);

namespace Maximaster\BitrixTableClasses\Table\Iblock;

use Bitrix\Iblock\SectionElementTable as BitrixSectionElementTable;
use Bitrix\Main\ORM\Fields\ExpressionField;
use Bitrix\Main\ORM\Fields\Field;
use Bitrix\Main\SystemException;
use Maximaster\BitrixTableClasses\Mixins\TableMapMergerTrait;

class SectionElementTable extends BitrixSectionElementTable
{
    use TableMapMergerTrait;

    public const IBLOCK_SECTION_ID = 'IBLOCK_SECTION_ID';
    public const IBLOCK_ELEMENT_ID = 'IBLOCK_ELEMENT_ID';
    public const ADDITIONAL_PROPERTY_ID = 'ADDITIONAL_PROPERTY_ID';
    public const IBLOCK_SECTION = 'IBLOCK_SECTION';
    public const IBLOCK_ELEMENT = 'IBLOCK_ELEMENT';
    public const ELEMENT_XML_ID = 'ELEMENT_XML_ID';
    public const SECTION_XML_ID = 'SECTION_XML_ID';
    public const ELEMENT_SORT = 'ELEMENT_SORT';
    public const ELEMENT_ACTIVE = 'ELEMENT_ACTIVE';

    /**
     * @throws SystemException
     *
     * @psalm-return array<string|int, Field|array<non-empty-string, mixed>>
     */
    public static function getMap(): array
    {
        return self::mergeMaps(parent::getMap(), [
            self::ELEMENT_XML_ID => new ExpressionField(
                self::ELEMENT_XML_ID,
                '%s',
                self::IBLOCK_ELEMENT . '.' . ElementTable::XML_ID
            ),
            self::SECTION_XML_ID => new ExpressionField(
                self::SECTION_XML_ID,
                '%s',
                self::IBLOCK_SECTION . '.' . SectionTable::XML_ID
            ),
            self::ELEMENT_SORT => new ExpressionField(
                self::ELEMENT_SORT,
                '%s',
                self::IBLOCK_ELEMENT . '.' . ElementTable::SORT
            ),
            self::ELEMENT_ACTIVE => new ExpressionField(
                self::ELEMENT_ACTIVE,
                '%s',
                self::IBLOCK_ELEMENT . '.' . ElementTable::ACTIVE
            ),
        ]);
    }
}
