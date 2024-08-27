<?php

declare(strict_types=1);

namespace Maximaster\BitrixTableClasses\Table\Main;

use Bitrix\Main\OperationTable as BtcOperationTable;
use Maximaster\BitrixTableClasses\Mixins\Dmbat;

class OperationTable extends BtcOperationTable
{
    use Dmbat;

    public const ID = 'ID';
    public const NAME = 'NAME';
    public const MODULE_ID = 'MODULE_ID';
    public const DESCRIPTION = 'DESCRIPTION';
    public const BINDING = 'BINDING';
}
