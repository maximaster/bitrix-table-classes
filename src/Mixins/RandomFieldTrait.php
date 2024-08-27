<?php

declare(strict_types=1);

namespace Maximaster\BitrixTableClasses\Mixins;

use Bitrix\Main\Entity\ExpressionField;

trait RandomFieldTrait
{
    /**
     * @psalm-param array<array-key, mixed> $map
     */
    private static function mixRandomMapField(&$map): void
    {
        $map['rand'] = new ExpressionField('rand', 'RAND()');
    }
}
