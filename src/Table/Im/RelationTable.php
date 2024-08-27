<?php

declare(strict_types=1);

namespace Maximaster\BitrixTableClasses\Table\Im;

use Bitrix\Im\Model\RelationTable as BitrixRelationTable;
use Maximaster\BitrixTableClasses\Mixins\Dmbat;

/**
 * Таблица пользователелей относящихся к чату.
 */
class RelationTable extends BitrixRelationTable
{
    use Dmbat;

    public const ID = 'ID';
    public const CHAT_ID = 'CHAT_ID';
    public const MESSAGE_TYPE = 'MESSAGE_TYPE';
    public const USER_ID = 'USER_ID';
    public const START_ID = 'START_ID';
    public const LAST_ID = 'LAST_ID';
    public const LAST_SEND_ID = 'LAST_SEND_ID';
    public const LAST_FILE_ID = 'LAST_FILE_ID';
    public const LAST_READ = 'LAST_READ';
    public const STATUS = 'STATUS';
    public const CALL_STATUS = 'CALL_STATUS';
    public const NOTIFY_BLOCK = 'NOTIFY_BLOCK';
    public const MANAGER = 'MANAGER';
    public const COUNTER = 'COUNTER';
    public const MESSAGE_STATUS = 'MESSAGE_STATUS';
    public const UNREAD_ID = 'UNREAD_ID';
    public const CHAT = 'CHAT';
    public const START = 'START';
    public const LAST_SEND = 'LAST_SEND';
    public const LAST = 'LAST';
    public const USER = 'USER';
}
