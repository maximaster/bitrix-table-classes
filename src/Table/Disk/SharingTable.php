<?php

declare(strict_types=1);

namespace Maximaster\BitrixTableClasses\Table\Disk;

use Bitrix\Disk\Internals\DataManager;
use Bitrix\Disk\Internals\SharingTable as BaseSharingTable;
use Bitrix\Main\Entity\Result;
use Bitrix\Main\ORM\Fields\Field;

/**
 * Переопределяем базовый класс SharingTable для модуля "Disk".
 *
 * Базовый класс SharingTable запрещает переопределение в дочерних классах (является final).
 */
class SharingTable extends DataManager
{
    /**
     * Расшаривание объекта без отклика пользователя (которому расшаривали).
     */
    public const STATUS_UNREPLIED = BaseSharingTable::STATUS_IS_UNREPLIED;

    /**
     * Расшаривание объекта одобрено пользователем (которому расшаривали).
     */
    public const STATUS_APPROVED = BaseSharingTable::STATUS_IS_APPROVED;

    /**
     * Расшаривание объекта отклонено пользователем(которому расшаривали).
     */
    public const STATUS_DECLINED = BaseSharingTable::STATUS_IS_DECLINED;

    /**
     * Расшаренно пользователю.
     */
    public const SHARE_TO_USER = BaseSharingTable::TYPE_TO_USER;

    /**
     * Расшаренно группе.
     */
    public const SHARE_TO_GROUP = BaseSharingTable::TYPE_TO_GROUP;

    /**
     * Расшаренно подразделению.
     */
    public const SHARE_TO_DEPARTMENT = BaseSharingTable::TYPE_TO_DEPARTMENT;

    public const ID = 'ID';
    public const CREATED_BY = 'CREATED_BY';
    public const CREATE_USER = 'CREATE_USER';
    public const TO_ENTITY = 'TO_ENTITY';
    public const FROM_ENTITY = 'FROM_ENTITY';
    public const PARENT_ID = 'PARENT_ID';
    public const LINK_OBJECT_ID = 'LINK_OBJECT_ID';
    public const LINK_STORAGE_ID = 'LINK_STORAGE_ID';
    public const LINK_STORAGE = 'LINK_STORAGE';
    public const LINK_OBJECT = 'LINK_OBJECT';
    public const REAL_STORAGE_ID = 'REAL_STORAGE_ID';
    public const REAL_STORAGE = 'REAL_STORAGE';
    public const REAL_OBJECT_ID = 'REAL_OBJECT_ID';
    public const REAL_OBJECT = 'REAL_OBJECT';
    public const DESCRIPTION = 'DESCRIPTION';
    public const CAN_FORWARD = 'CAN_FORWARD';
    public const TYPE = 'TYPE';
    public const STATUS = 'STATUS';
    public const TASK_NAME = 'TASK_NAME';
    public const PATH_PARENT_REAL_OBJECT = 'PATH_PARENT_REAL_OBJECT';
    public const PATH_CHILD_REAL_OBJECT = 'PATH_CHILD_REAL_OBJECT';
    public const PATH_CHILD_REAL_OBJECT_SOFT = 'PATH_CHILD_REAL_OBJECT_SOFT';
    public const PATH_PARENT_LINK_OBJECT = 'PATH_PARENT_LINK_OBJECT';
    public const PATH_CHILD_LINK_OBJECT = 'PATH_CHILD_LINK_OBJECT';

    public static function checkFields(Result $result, $primary, array $data)
    {
        BaseSharingTable::checkFields($result, $primary, $data);
    }

    public static function getTableName(): string
    {
        return BaseSharingTable::getTableName();
    }

    /**
     * @psalm-return array<string|int, Field|array<non-empty-string, mixed>>
     */
    public static function getMap(): array
    {
        return BaseSharingTable::getMap();
    }

    /**
     * @psalm-param array<string, mixed> $fields
     * @psalm-param array<string, mixed> $fields
     */
    public static function updateBatch(array $fields, array $filter): void
    {
        BaseSharingTable::updateBatch($fields, $filter);
    }
}
