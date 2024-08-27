<?php

declare(strict_types=1);

namespace Maximaster\BitrixTableClasses\Table\Workflow;

use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\Entity\IntegerField;
use Bitrix\Main\ORM\Fields\Field;
use Exception;
use Maximaster\BitrixTableClasses\Mixins\Dmbat;

class WorkflowStatusAccessTable extends DataManager
{
    use Dmbat;

    public static function getTableName(): string
    {
        return 'b_workflow_status2group';
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
        return [
            'ID' => new IntegerField('ID', [
                'primary' => true,
                'autocomplete' => true,
            ]),
            'STATUS_ID' => new IntegerField('STATUS_ID', [
                'required' => true,
            ]),
            'GROUP_ID' => new IntegerField('GROUP_ID', [
                'required' => true,
            ]),
            'PERMISSION_TYPE' => new IntegerField('PERMISSION_TYPE', [
                'required' => true,
            ]),
        ];
    }
}
