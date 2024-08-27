<?php

declare(strict_types=1);

namespace Maximaster\BitrixTableClasses\Table\Disk;

use Bitrix\Disk\Internals\DataManager;
use Bitrix\Main\ORM\Fields\BooleanField;
use Bitrix\Main\ORM\Fields\Field;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\ORM\Fields\Validators\LengthValidator;
use Bitrix\Main\ORM\Query\Join;
use Maximaster\BitrixEnums\Main\Orm\JoinType;
use Maximaster\BitrixTableClasses\Mixins\Dmbat;

/**
 * Таблица прав на объекты диска.
 * Не наследует от @Bitrix\Disk\Internals\RightTable, т.к. oн final.
 */
class RightTable extends DataManager
{
    use Dmbat;

    public const ID = 'ID';
    public const OBJECT_ID = 'OBJECT_ID';
    public const TASK_ID = 'TASK_ID';
    public const ACCESS_CODE = 'ACCESS_CODE';
    public const DOMAIN = 'DOMAIN';
    public const NEGATIVE = 'NEGATIVE';
    public const OBJECT = 'OBJECT';

    public static function getTableName(): string
    {
        return 'b_disk_right';
    }

    /**
     * Returns entity map definition
     * return array.
     *
     * @psalm-return array<string|int, Field|array<non-empty-string, mixed>>
     */
    public static function getMap(): array
    {
        return [
            self::ID => (new IntegerField(self::ID))
                ->configurePrimary(true)
                ->configureAutocomplete(true),
            self::OBJECT_ID => (new IntegerField(self::OBJECT_ID))
                ->configureRequired(true),
            self::TASK_ID => (new IntegerField(self::TASK_ID))
                ->configureRequired(true),
            self::ACCESS_CODE => (new StringField(self::ACCESS_CODE))
                ->configureRequired(true)
                ->addValidator(new LengthValidator(1, 50)),
            self::DOMAIN => (new StringField(self::DOMAIN))
                ->addValidator(new LengthValidator(null, 50)),
            self::NEGATIVE => (new BooleanField(self::NEGATIVE))
                ->configureValues(0, 1)
                ->configureDefaultValue(0),
            self::OBJECT => (new Reference(
                self::OBJECT,
                ObjectTable::class,
                Join::on('this.' . self::OBJECT_ID, 'ref.' . ObjectTable::ID)
            ))->configureJoinType(JoinType::INNER),
// Добавить по мере необходимости
//             'PATH_PARENT' => array(
//                 'data_type' => '\Bitrix\Disk\Internals\ObjectPathTable',
//                 'reference' => array(
//                     '=this.OBJECT_ID' => 'ref.PARENT_ID'
//                 ),
//                 'join_type' => 'INNER',
//             ),
//             'PATH_CHILD' => array(
//                 'data_type' => '\Bitrix\Disk\Internals\ObjectPathTable',
//                 'reference' => array(
//                     '=this.OBJECT_ID' => 'ref.OBJECT_ID'
//                 ),
//                 'join_type' => 'INNER',
//             ),
//             'TASK_OPERATION' => array(
//                 'data_type' => '\Bitrix\Main\TaskOperationTable',
//                 'reference' => array(
//                     '=this.TASK_ID' => 'ref.TASK_ID'
//                 ),
//                 'join_type' => 'INNER',
//             ),
//             'USER_ACCESS' => array(
//                 'data_type' => '\Bitrix\Main\UserAccessTable',
//                 'reference' => array(
//                     '=this.ACCESS_CODE' => 'ref.ACCESS_CODE'
//                 ),
//             ),
        ];
    }
}
