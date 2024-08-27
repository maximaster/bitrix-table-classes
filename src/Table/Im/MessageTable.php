<?php

declare(strict_types=1);

namespace Maximaster\BitrixTableClasses\Table\Im;

use Bitrix\Im\Model\MessageTable as BitrixMessageTable;
use Maximaster\BitrixTableClasses\Mixins\Dmbat;

/**
 * Таблица хранения сообщений.
 */
class MessageTable extends BitrixMessageTable
{
    use Dmbat;

    public const ID = 'ID';
    public const CHAT_ID = 'CHAT_ID';
    public const AUTHOR_ID = 'AUTHOR_ID';
    public const AUTHOR = 'AUTHOR';
    public const MESSAGE = 'MESSAGE';
    public const MESSAGE_OUT = 'MESSAGE_OUT';
    public const DATE_CREATE = 'DATE_CREATE';
    public const EMAIL_TEMPLATE = 'EMAIL_TEMPLATE';
    public const NOTIFY_TYPE = 'NOTIFY_TYPE';
    public const NOTIFY_MODULE = 'NOTIFY_MODULE';
    public const NOTIFY_EVENT = 'NOTIFY_EVENT';
    public const NOTIFY_TAG = 'NOTIFY_TAG';
    public const NOTIFY_SUB_TAG = 'NOTIFY_SUB_TAG';
    public const NOTIFY_TITLE = 'NOTIFY_TITLE';
    public const NOTIFY_BUTTONS = 'NOTIFY_BUTTONS';
    public const NOTIFY_READ = 'NOTIFY_READ';
    public const IMPORT_ID = 'IMPORT_ID';
}
