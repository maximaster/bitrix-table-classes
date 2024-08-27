<?php

declare(strict_types=1);

namespace Maximaster\BitrixTableClasses\Table\Main;

use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\ORM\Fields\BooleanField;
use Bitrix\Main\ORM\Fields\Field;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\ORM\Query\Join;
use Bitrix\Main\SystemException;
use Maximaster\BitrixEnums\Main\Orm\JoinType;
use Maximaster\BitrixTableClasses\Mixins\Dmbat;

/**
 * Описание таблицы с правами на инфоблоки.
 */
class IblockRightsTable extends DataManager
{
    use Dmbat;

    public const ID = 'ID';
    public const IBLOCK_ID = 'IBLOCK_ID';
    public const GROUP_CODE = 'GROUP_CODE';
    public const ENTITY_TYPE = 'ENTITY_TYPE';
    public const ENTITY_ID = 'ENTITY_ID';
    public const DO_INHERIT = 'DO_INHERIT';
    public const TASK_ID = 'TASK_ID';
    public const TASK = 'TASK';
    public const OP_SREAD = 'OP_SREAD';
    public const OP_EREAD = 'OP_EREAD';
    public const XML_ID = 'XML_ID';

    public static function getTableName(): string
    {
        return 'b_iblock_right';
    }

    /**
     * @return Field[]
     * @psalm-return array<string|int, Field|array<non-empty-string, mixed>>
     *
     * @throws SystemException
     */
    public static function getMap(): array
    {
        return [
            self::ID => (
                new IntegerField(self::ID)
            )
                ->configureAutocomplete(true)
                ->configurePrimary(true),
            self::IBLOCK_ID => (
                new IntegerField(self::IBLOCK_ID)
            )
                ->configureRequired(true),
            self::GROUP_CODE => (
                new StringField(self::GROUP_CODE)
            )
                ->configureRequired(true),
            self::ENTITY_TYPE => (
                new StringField(self::ENTITY_TYPE)
            )
                ->configureRequired(true),
            self::ENTITY_ID => (
                new IntegerField(self::ENTITY_ID)
            )
                ->configureRequired(true),
            self::DO_INHERIT => (
                new BooleanField(self::DO_INHERIT)
            )
                ->configureValues('N', 'Y'),
            self::TASK_ID => (
                new IntegerField(self::TASK_ID)
            )
                ->configureRequired(true),
            self::TASK => (new Reference(
                self::TASK,
                TaskOperationTable::class,
                Join::on('this.' . self::TASK_ID, 'ref.' . TaskOperationTable::TASK_ID)
            ))
                ->configureJoinType(JoinType::INNER),
            self::OP_SREAD => (
                new BooleanField(self::OP_SREAD)
            )
                ->configureValues('N', 'Y'),
            self::OP_EREAD => (
                new BooleanField(self::OP_EREAD)
            )
                ->configureValues('N', 'Y'),
            self::XML_ID => (
                new StringField(self::XML_ID)
            ),
        ];
    }
}
