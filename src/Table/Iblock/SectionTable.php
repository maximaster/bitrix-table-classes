<?php

declare(strict_types=1);

namespace Maximaster\BitrixTableClasses\Table\Iblock;

use Bitrix\Main\ORM\Fields\Field;
use Bitrix\Main\SystemException;
use Bitrix\Main\Type\DateTime;
use Maximaster\BitrixTableClasses\Mixins\DataManagerForwarderTrait;
use Maximaster\BitrixTableClasses\Mixins\Dmbat;
use Maximaster\BitrixTableClasses\Mixins\RandomFieldTrait;
use Maximaster\BitrixTableFields\Field\LookupField;

class SectionTable extends \Bitrix\Iblock\SectionTable
{
    use IblockTableTrait;
    use DataManagerForwarderTrait;
    use RandomFieldTrait;
    use Dmbat;

    public const ID = 'ID';
    public const TIMESTAMP_X = 'TIMESTAMP_X';
    public const MODIFIED_BY = 'MODIFIED_BY';
    public const DATE_CREATE = 'DATE_CREATE';
    public const CREATED_BY = 'CREATED_BY';
    public const IBLOCK = 'IBLOCK';
    public const IBLOCK_ID = 'IBLOCK_ID';
    public const IBLOCK_SECTION_ID = 'IBLOCK_SECTION_ID';
    public const ACTIVE = 'ACTIVE';
    public const GLOBAL_ACTIVE = 'GLOBAL_ACTIVE';
    public const NAME = 'NAME';
    public const PICTURE = 'PICTURE';
    public const LEFT_MARGIN = 'LEFT_MARGIN';
    public const RIGHT_MARGIN = 'RIGHT_MARGIN';
    public const DEPTH_LEVEL = 'DEPTH_LEVEL';
    public const DESCRIPTION = 'DESCRIPTION';
    public const DESCRIPTION_TYPE = 'DESCRIPTION_TYPE';
    public const SEARCHABLE_CONTENT = 'SEARCHABLE_CONTENT';
    public const CODE = 'CODE';
    public const XML_ID = 'XML_ID';
    public const TMP_ID = 'TMP_ID';
    public const DETAIL_PICTURE = 'DETAIL_PICTURE';
    public const SOCNET_GROUP_ID = 'SOCNET_GROUP_ID';
    public const PARENT_SECTION = 'PARENT_SECTION';
    public const CREATED_BY_USER = 'CREATED_BY_USER';
    public const MODIFIED_BY_USER = 'MODIFIED_BY_USER';
    public const SORT = 'SORT';
    public const IBLOCK_TYPE = 'IBLOCK_TYPE';
    public const IBLOCK_XML_ID = 'IBLOCK_XML_ID';

    public static function getUfId(): ?string
    {
        $iblockId = static::getIblockId();

        return $iblockId === null ? null : "IBLOCK_{$iblockId}_SECTION";
    }

    /**
     * {@inheritDoc}
     *
     * @return Field[]|array[]
     *
     * @throws SystemException
     *
     * @psalm-return array<non-empty-string|int, array<non-empty-string, mixed>|Field>
     */
    public static function getMap(): array
    {
        $map = array_merge(parent::getMap(), [
            self::IBLOCK_TYPE => LookupField::for(
                self::IBLOCK_TYPE,
                self::IBLOCK,
                IBlockTable::IBLOCK_TYPE_ID
            ),
            self::IBLOCK_XML_ID => LookupField::for(
                self::IBLOCK_XML_ID,
                self::IBLOCK,
                IBlockTable::XML_ID
            ),
        ]);

        if (array_key_exists('TIMESTAMP_X', $map) && array_key_exists('default_value', $map['TIMESTAMP_X']) === false) {
            $map['TIMESTAMP_X']['default_value'] = static fn () => new DateTime();
        }

        self::mixRandomMapField($map);

        return $map;
    }
}
