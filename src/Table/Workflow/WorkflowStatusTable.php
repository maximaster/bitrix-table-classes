<?php

declare(strict_types=1);

namespace Maximaster\BitrixTableClasses\Table\Workflow;

use Bitrix\Main\Entity\BooleanField;
use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\Entity\DatetimeField;
use Bitrix\Main\Entity\Field;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\Entity\StringField;
use Bitrix\Main\Type\DateTime;
use Exception;
use Maximaster\BitrixTableClasses\Mixins\Dmbat;

class WorkflowStatusTable extends DataManager
{
    use Dmbat;

    public static function getTableName(): string
    {
        return 'b_workflow_status';
    }

    /**
     * @return Field[]
     *
     * @throws Exception
     *
     * @psalm-return array<string|int, Field|array<non-empty-string, mixed>>
     */
    public static function getMap(): array
    {
        // @phpstan-ignore-next-line why:false-positive
        return [
            'ID' => new IntegerField('ID', [
                'primary' => true,
                'autocomplete' => true,
            ]),
            'TIMESTAMP_X' => new DatetimeField('TIMESTAMP_X', [
                    'default_value' => new DateTime(),
                ]),
            'C_SORT' => new IntegerField('C_SORT'),
            'ACTIVE' => new BooleanField('ACTIVE', [
                'values' => ['N', 'Y'],
                'default_value' => 'Y',
            ]),
            'TITLE' => new StringField('TITLE', [
                'required' => true,
            ]),
            'DESCRIPTION' => new StringField('DESCRIPTION'),
            'IS_FINAL' => new BooleanField('IS_FINAL', [
                'values' => ['N', 'Y'],
                'default_value' => 'N',
            ]),
            'NOTIFY' => new BooleanField('NOTIFY', [
                'values' => ['N', 'Y'],
                'default_value' => 'N',
            ]),
        ];
    }
}
