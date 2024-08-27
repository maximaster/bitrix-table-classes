<?php

declare(strict_types=1);

namespace Maximaster\BitrixTableClasses\Table\Main;

use Bitrix\Main\GroupTable as BaseGroupTable;
use Maximaster\BitrixTableClasses\Mixins\Dmbat;

/**
 * Группы пользователей.
 */
class GroupTable extends BaseGroupTable
{
    use Dmbat;

    public const ID = 'ID';
    public const TIMESTAMP_X = 'TIMESTAMP_X';
    public const ACTIVE = 'ACTIVE';
    public const C_SORT = 'C_SORT';
    public const ANONYMOUS = 'ANONYMOUS';
    public const NAME = 'NAME';
    public const DESCRIPTION = 'DESCRIPTION';
    public const SECURITY_POLICY = 'SECURITY_POLICY';
    public const STRING_ID = 'STRING_ID';
    public const IS_SYSTEM = 'IS_SYSTEM';
}
