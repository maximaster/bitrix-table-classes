<?php

declare(strict_types=1);

namespace Maximaster\BitrixTableClasses\Table\Main;

use Bitrix\Main\UserGroupTable as BitrixUserGroupTable;
use Maximaster\BitrixTableClasses\Mixins\Dmbat;

/**
 * Таблица связей между группами и пользователями.
 */
class UserGroupTable extends BitrixUserGroupTable
{
    use Dmbat;

    public const USER_ID = 'USER_ID';
    public const GROUP_ID = 'GROUP_ID';
    public const DATE_ACTIVE_FROM = 'DATE_ACTIVE_FROM';
    public const DATE_ACTIVE_TO = 'DATE_ACTIVE_TO';
    public const USER = 'USER';
    public const GROUP = 'GROUP';
}
