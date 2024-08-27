<?php

declare(strict_types=1);

namespace Maximaster\BitrixTableClasses\Table\Iblock;

use Bitrix\Iblock\ElementTable;
use Bitrix\Iblock\PropertyEnumerationTable;
use Bitrix\Iblock\PropertyTable;
use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\Entity\ReferenceField;
use Bitrix\Main\Entity\StringField;
use Bitrix\Main\ORM\Fields\Field;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\TextField;
use Maximaster\BitrixEnums\Main\Orm\JoinType;
use Maximaster\BitrixTableClasses\Mixins\Dmbat;

class ElementV1PropertyTable extends DataManager
{
    use Dmbat;

    public static function getTableName()
    {
        return 'b_iblock_element_property';
    }

    /**
     * @psalm-return array<string|int, Field|array<non-empty-string, mixed>>
     */
    public static function getMap()
    {
        return [
            'ID' => new IntegerField('ID', ['primary' => true, 'autocomplete' => true]),
            'IBLOCK_PROPERTY_ID' => new IntegerField('IBLOCK_PROPERTY_ID'),
            'IBLOCK_ELEMENT_ID' => new IntegerField('IBLOCK_ELEMENT_ID'),
            'VALUE' => new TextField('VALUE'),
            'VALUE_TYPE' => new StringField('VALUE_TYPE'),
            'VALUE_ENUM' => new IntegerField('VALUE_ENUM'),
            'VALUE_NUM' => new IntegerField('VALUE_NUM'),
            'DESCRIPTION' => new IntegerField('DESCRIPTION'),

            'PROPERTY' => new ReferenceField(
                'PROPERTY',
                PropertyTable::class,
                ['=this.IBLOCK_PROPERTY_ID' => 'ref.ID']
            ),
            'ENUM' => new ReferenceField(
                'ENUM',
                PropertyEnumerationTable::class,
                ['=this.VALUE_ENUM' => 'ref.ID'],
                ['join_type' => JoinType::LEFT]
            ),
            'ELEMENT' => new ReferenceField('ELEMENT', ElementTable::class, [
                '=this.IBLOCK_ELEMENT_ID' => 'ref.ID',
            ]),
        ];
    }
}
