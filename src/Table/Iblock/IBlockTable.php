<?php

declare(strict_types=1);

namespace Maximaster\BitrixTableClasses\Table\Iblock;

use Bitrix\Iblock\IblockTable as BitrixIBlockTable;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\ORM\Fields\Field;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;
use Bitrix\Main\SystemException;
use Maximaster\BitrixTableClasses\Mixins\DataManagerForwarderTrait;
use Maximaster\BitrixTableClasses\Mixins\Dmbat;
use Maximaster\BitrixTableClasses\Mixins\TableMapMergerTrait;

class IBlockTable extends BitrixIBlockTable
{
    use DataManagerForwarderTrait;
    use Dmbat;
    use TableMapMergerTrait;

    public const ID = 'ID';
    public const TIMESTAMP_X = 'TIMESTAMP_X';
    public const IBLOCK_TYPE_ID = 'IBLOCK_TYPE_ID';
    public const LID = 'LID';
    public const CODE = 'CODE';
    public const NAME = 'NAME';
    public const ACTIVE = 'ACTIVE';
    public const SORT = 'SORT';
    public const LIST_PAGE_URL = 'LIST_PAGE_URL';
    public const DETAIL_PAGE_URL = 'DETAIL_PAGE_URL';
    public const SECTION_PAGE_URL = 'SECTION_PAGE_URL';
    public const CANONICAL_PAGE_URL = 'CANONICAL_PAGE_URL';
    public const PICTURE = 'PICTURE';
    public const DESCRIPTION = 'DESCRIPTION';
    public const DESCRIPTION_TYPE = 'DESCRIPTION_TYPE';
    public const XML_ID = 'XML_ID';
    public const TMP_ID = 'TMP_ID';
    public const INDEX_ELEMENT = 'INDEX_ELEMENT';
    public const INDEX_SECTION = 'INDEX_SECTION';
    public const WORKFLOW = 'WORKFLOW';
    public const BIZPROC = 'BIZPROC';
    public const SECTION_CHOOSER = 'SECTION_CHOOSER';
    public const LIST_MODE = 'LIST_MODE';
    public const RIGHTS_MODE = 'RIGHTS_MODE';
    public const SECTION_PROPERTY = 'SECTION_PROPERTY';
    public const PROPERTY_INDEX = 'PROPERTY_INDEX';
    public const VERSION = 'VERSION';
    public const LAST_CONV_ELEMENT = 'LAST_CONV_ELEMENT';
    public const SOCNET_GROUP_ID = 'SOCNET_GROUP_ID';
    public const EDIT_FILE_BEFORE = 'EDIT_FILE_BEFORE';
    public const EDIT_FILE_AFTER = 'EDIT_FILE_AFTER';
    public const TYPE = 'TYPE';
    public const SECTIONS_NAME = 'SECTIONS_NAME';
    public const SECTION_NAME = 'SECTION_NAME';
    public const ELEMENTS_NAME = 'ELEMENTS_NAME';
    public const ELEMENT_NAME = 'ELEMENT_NAME';
    public const RSS_TTL = 'RSS_TTL';
    public const RSS_ACTIVE = 'RSS_ACTIVE';
    public const RSS_FILE_ACTIVE = 'RSS_FILE_ACTIVE';
    public const RSS_FILE_LIMIT = 'RSS_FILE_LIMIT';
    public const RSS_FILE_DAYS = 'RSS_FILE_DAYS';
    public const RSS_YANDEX_ACTIVE = 'RSS_YANDEX_ACTIVE';

    /**
     * {@inheritDoc}
     *
     * @return Field[]|array[]
     *
     * @throws ArgumentException
     * @throws SystemException
     *
     * @psalm-return array<non-empty-string|int, Field|array<non-empty-string, mixed>>
     */
    public static function getMap(): array
    {
        return array_merge(parent::getMap(), [
            self::TYPE => new Reference(
                self::TYPE,
                self::class,
                Join::on('this.' . self::IBLOCK_TYPE_ID, 'ref.' . IBlockTypeTable::ID)
            ),
        ]);
    }
}
