<?php

declare(strict_types=1);

namespace Maximaster\BitrixTableClasses\Table\Im;

use Bitrix\Im\Model\ChatTable as BitrixChatTable;
use Maximaster\BitrixTableClasses\Mixins\Dmbat;

/**
 * Таблица хранения чатов.
 */
class ChatTable extends BitrixChatTable
{
    use Dmbat;

    public const ID = 'ID';
    public const PARENT_ID = 'PARENT_ID';
    public const PARENT_MID = 'PARENT_MID';
    public const TITLE = 'TITLE';
    public const DESCRIPTION = 'DESCRIPTION';
    public const COLOR = 'COLOR';
    public const TYPE = 'TYPE';
    public const EXTRANET = 'EXTRANET';
    public const AUTHOR_ID = 'AUTHOR_ID';
    public const AVATAR = 'AVATAR';
    public const CALL_TYPE = 'CALL_TYPE';
    public const CALL_NUMBER = 'CALL_NUMBER';
    public const ENTITY_TYPE = 'ENTITY_TYPE';
    public const ENTITY_ID = 'ENTITY_ID';
    public const ENTITY_DATA_1 = 'ENTITY_DATA_1';
    public const ENTITY_DATA_2 = 'ENTITY_DATA_2';
    public const ENTITY_DATA_3 = 'ENTITY_DATA_3';
    public const DISK_FOLDER_ID = 'DISK_FOLDER_ID';
    public const LAST_MESSAGE_ID = 'LAST_MESSAGE_ID';
    public const DATE_CREATE = 'DATE_CREATE';
    public const PIN_MESSAGE_ID = 'PIN_MESSAGE_ID';
    public const LAST_MESSAGE_STATUS = 'LAST_MESSAGE_STATUS';
    public const MESSAGE_COUNT = 'MESSAGE_COUNT';
}
