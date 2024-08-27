<?php

declare(strict_types=1);

namespace Maximaster\BitrixTableClasses\Table\Main;

use Bitrix\Main\UserFieldTable as BitrixUserFieldTable;
use Maximaster\BitrixTableClasses\Mixins\DataManagerForwarderTrait;
use Maximaster\BitrixTableClasses\Mixins\Dmbat;
use Maximaster\BitrixTableClasses\Mixins\TableMapMergerTrait;

/**
 * Таблица пользовательских полей.
 */
class UserFieldTable extends BitrixUserFieldTable
{
    use TableMapMergerTrait;
    use DataManagerForwarderTrait;
    use Dmbat;

    public const ID = 'ID';
    public const ENTITY_ID = 'ENTITY_ID';
    public const FIELD_NAME = 'FIELD_NAME';
    public const USER_TYPE_ID = 'USER_TYPE_ID';
    public const XML_ID = 'XML_ID';
    public const SORT = 'SORT';
    public const MULTIPLE = 'MULTIPLE';
    public const MANDATORY = 'MANDATORY';
    public const SHOW_FILTER = 'SHOW_FILTER';
    public const SHOW_IN_LIST = 'SHOW_IN_LIST';
    public const EDIT_IN_LIST = 'EDIT_IN_LIST';
    public const IS_SEARCHABLE = 'IS_SEARCHABLE';
    public const SETTINGS = 'SETTINGS';

    public static function getTableName(): string
    {
        return 'b_user_field';
    }
}
