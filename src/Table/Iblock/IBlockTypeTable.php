<?php

declare(strict_types=1);

namespace Maximaster\BitrixTableClasses\Table\Iblock;

use Bitrix\Main\ArgumentTypeException;
use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\ORM\Fields\Field;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\ORM\Fields\Validators\LengthValidator;
use Bitrix\Main\SystemException;
use Maximaster\BitrixEnums\Main\Truth;
use Maximaster\BitrixTableClasses\Mixins\DataManagerForwarderTrait;
use Maximaster\BitrixTableClasses\Mixins\Dmbat;
use Maximaster\BitrixTableClasses\Mixins\TableMapMergerTrait;

class IBlockTypeTable extends DataManager
{
    use DataManagerForwarderTrait;
    use Dmbat;
    use TableMapMergerTrait;

    public const ID = 'ID';
    public const SECTIONS = 'SECTIONS';
    public const EDIT_FILE_BEFORE = 'EDIT_FILE_BEFORE';
    public const EDIT_FILE_AFTER = 'EDIT_FILE_AFTER';
    public const IN_RSS = 'IN_RSS';
    public const SORT = 'SORT';

    public static function getTableName(): string
    {
        return 'b_iblock_type';
    }

    /**
     * @throws ArgumentTypeException
     * @throws SystemException
     *
     * @psalm-return array<string|int, Field|array<non-empty-string, mixed>>
     */
    public static function getMap(): array
    {
        return [
            self::ID => (
                new StringField(self::ID)
            )
                ->configurePrimary(true)
                ->addValidator(new LengthValidator(1, 50)),
            self::SECTIONS => (
                new StringField(self::SECTIONS)
            )
                ->configureSize(1)
                ->configureDefaultValue(Truth::YES)
                ->addValidator(new LengthValidator(null, 1)),
            self::EDIT_FILE_BEFORE => (
                new StringField(self::EDIT_FILE_BEFORE)
            )
                ->addValidator(new LengthValidator(null, 255)),
            self::EDIT_FILE_AFTER => (
                new StringField(self::EDIT_FILE_AFTER)
            )
                ->addValidator(new LengthValidator(null, 255)),
            self::IN_RSS => (
                new StringField(self::IN_RSS)
            )
                ->configureSize(1)
                ->configureDefaultValue(Truth::NO)
                ->addValidator(new LengthValidator(null, 1)),
            self::SORT => (
                new IntegerField(self::SORT)
            )
                ->configureDefaultValue(500),
        ];
    }
}
