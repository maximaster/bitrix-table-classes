<?php

declare(strict_types=1);

namespace Maximaster\BitrixTableClasses\Table\Search;

use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields\DatetimeField;
use Bitrix\Main\ORM\Fields\Field;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\ORM\Fields\TextField;
use Bitrix\Main\ORM\Fields\Validators\LengthValidator;
use Bitrix\Main\ORM\Query\Join;
use Exception;
use Maximaster\BitrixTableClasses\Table\Main\UserTable;
use Maximaster\BitrixTableClasses\Mixins\Dmbat;

class SearchContentTable extends DataManager
{
    use Dmbat;

    public const ID = 'ID';
    public const DATE_CHANGE = 'DATE_CHANGE';
    public const MODULE_ID = 'MODULE_ID';
    public const ITEM_ID = 'ITEM_ID';
    public const CUSTOM_RANK = 'CUSTOM_RANK';
    public const USER_ID = 'USER_ID';
    public const ENTITY_TYPE_ID = 'ENTITY_TYPE_ID';
    public const ENTITY_ID = 'ENTITY_ID';
    public const URL = 'URL';
    public const TITLE = 'TITLE';
    public const BODY = 'BODY';
    public const TAGS = 'TAGS';
    public const PARAM1 = 'PARAM1';
    public const PARAM2 = 'PARAM2';
    public const UPD = 'UPD';
    public const DATE_FROM = 'DATE_FROM';
    public const DATE_TO = 'DATE_TO';
    public const USER = 'USER';

    /**
     * Разделитель тегов.
     */
    public const TAGS_DELIMITER = ', ';

    public static function getTableName(): string
    {
        return 'b_search_content';
    }

    /**
     * @return Field[]
     *
     * @throws Exception
     *
     * @psalm-return array<string|int, Field|array<non-empty-string, mixed>>
     */
    public static function getMap(): array
    {
        return [
            self::ID => (new IntegerField(self::ID))
                ->configurePrimary(true)
                ->configureAutocomplete(true),
            self::DATE_CHANGE => (new DatetimeField(self::DATE_CHANGE))
                ->configureRequired(true),
            self::MODULE_ID => (new StringField(self::MODULE_ID))
                ->configureRequired(true)
                ->addValidator(new LengthValidator(1, 50)),
            self::ITEM_ID => (new StringField(self::ITEM_ID))
                ->configureRequired(true),
            self::CUSTOM_RANK => (new IntegerField(self::CUSTOM_RANK))
                ->configureRequired(true),
            self::USER_ID => new IntegerField(self::USER_ID),
            self::ENTITY_TYPE_ID => new StringField(self::ENTITY_TYPE_ID),
            self::ENTITY_ID => new StringField(self::ENTITY_ID),
            self::URL => new TextField(self::URL),
            self::TITLE => new TextField(self::TITLE),
            self::BODY => new TextField(self::BODY),
            self::TAGS => new TextField(self::TAGS),
            self::PARAM1 => new StringField(self::PARAM1),
            self::PARAM2 => new StringField(self::PARAM2),
            self::UPD => (new StringField(self::UPD))
                ->addValidator(new LengthValidator(1, 50)),
            self::DATE_FROM => new DatetimeField(self::DATE_FROM),
            self::DATE_TO => new DatetimeField(self::DATE_TO),
            self::USER => new Reference(
                self::USER,
                UserTable::class,
                Join::on('this.' . self::USER_ID, 'ref.' . UserTable::ID)
            ),
        ];
    }

    /**
     * Подготавливает список тега в вид пригодных для хранения в базе данных.
     *
     * @psalm-param list<non-empty-string> $tags
     */
    public static function formatTags(array $tags): string
    {
        return implode(self::TAGS_DELIMITER, $tags);
    }
}
