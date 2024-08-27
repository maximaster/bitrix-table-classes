<?php

declare(strict_types=1);

namespace Maximaster\BitrixTableClasses\Table\Main;

use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\StringField;
use Bitrix\Main\ORM\Fields\Field;
use Maximaster\BitrixTableClasses\Mixins\DataManagerForwarderTrait;
use Maximaster\BitrixTableClasses\Mixins\Dmbat;
use Maximaster\BitrixTableClasses\Mixins\TableMapMergerTrait;

/**
 * Языковые фразы пользовательского поля.
 */
class UserFieldLangTable extends DataManager
{
    use TableMapMergerTrait;
    use DataManagerForwarderTrait;
    use Dmbat;

    public const USER_FIELD_ID = 'USER_FIELD_ID';
    public const LANGUAGE_ID = 'LANGUAGE_ID';
    public const EDIT_FORM_LABEL = 'EDIT_FORM_LABEL';
    public const LIST_COLUMN_LABEL = 'LIST_COLUMN_LABEL';
    public const LIST_FILTER_LABEL = 'LIST_FILTER_LABEL';
    public const ERROR_MESSAGE = 'ERROR_MESSAGE';
    public const HELP_MESSAGE = 'HELP_MESSAGE';

    public static function getTableName(): string
    {
        return 'b_user_field_lang';
    }

    /**
     * @psalm-return array<string|int, Field|array<non-empty-string, mixed>>
     */
    public static function getMap(): array
    {
        return [
            self::USER_FIELD_ID => (new IntegerField(self::USER_FIELD_ID))
                ->configureAutocomplete(true)
                ->configurePrimary(true),
            self::LANGUAGE_ID => (new StringField(self::LANGUAGE_ID)),
            self::EDIT_FORM_LABEL => (new StringField(self::EDIT_FORM_LABEL)),
            self::LIST_COLUMN_LABEL => (new StringField(self::LIST_COLUMN_LABEL)),
            self::LIST_FILTER_LABEL => (new StringField(self::LIST_FILTER_LABEL)),
            self::ERROR_MESSAGE => (new StringField(self::ERROR_MESSAGE)),
            self::HELP_MESSAGE => (new StringField(self::HELP_MESSAGE)),
        ];
    }
}
