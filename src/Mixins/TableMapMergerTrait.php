<?php

declare(strict_types=1);

namespace Maximaster\BitrixTableClasses\Mixins;

use Bitrix\Main\ORM\Fields\Field;

/**
 * Упрощает добавление полей при наследовании.
 */
trait TableMapMergerTrait
{
    /**
     * @psalm-param array<array-key, Field|array<non-empty-string, mixed>> $targetMap
     * @psalm-param array<array-key, Field|array<non-empty-string, mixed>> $mixedMap
     * @psalm-return array<array-key, Field|array<non-empty-string, mixed>>
     */
    public static function mergeMaps(array $targetMap, array $mixedMap): array
    {
        $originCodes = [];
        foreach ($targetMap as $originFieldIdx => $originField) {
            if (is_numeric($originFieldIdx) === false) {
                $originCodes[] = $originFieldIdx;
                continue;
            }

            if ($originField instanceof Field) {
                $originCodes[] = $originField->getName();
            }
        }

        foreach ($mixedMap as $mixedFieldIdx => $mixedField) {
            $mixedFieldCode = null;
            if (is_numeric($mixedFieldIdx) === false) {
                $mixedFieldCode = $mixedFieldIdx;
            } elseif ($mixedField instanceof Field) {
                $mixedFieldCode = $mixedField->getName();
            }

            if ($mixedFieldCode !== null) {
                $targetMap[$mixedFieldCode] = $mixedField;
            }
        }

        return $targetMap;
    }
}
