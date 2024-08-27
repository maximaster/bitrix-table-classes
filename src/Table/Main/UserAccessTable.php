<?php

declare(strict_types=1);

namespace Maximaster\BitrixTableClasses\Table\Main;

use Bitrix\Main\UserAccessTable as BitrixUserAccessTable;
use Maximaster\BitrixTableClasses\Mixins\Dmbat;

/**
 * Таблица кодов доступа пользователей.
 */
class UserAccessTable extends BitrixUserAccessTable
{
    use Dmbat;

    public const USER_ID = 'USER_ID';
    public const PROVIDER_ID = 'PROVIDER_ID';
    public const ACCESS_CODE = 'ACCESS_CODE';
}
