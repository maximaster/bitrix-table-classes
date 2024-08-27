<?php

declare(strict_types=1);

namespace Maximaster\BitrixTableClasses\Mixins;

use Bitrix\Main\ORM\Fields\Field;
use Bitrix\Main\ORM\Fields\Validators\LengthValidator;
use Bitrix\Main\SystemException;
use ReflectionObject;
use RuntimeException;

/**
 * Предоставляет метод замены валидатора.
 */
trait LengthValidatorReplacerTrait
{
    /**
     * @return Field[]
     *
     * @psalm-return array<string|int, Field|array<non-empty-string, mixed>>
     */
    abstract public static function getMap(): array;

    /**
     * @throws SystemException
     */
    public static function withReplacedLengthValidator(Field $field, LengthValidator $lengthValidator): Field
    {
        $lengthValidators = array_filter(
            $field->getValidators(),
            static fn (object $validator) => $validator instanceof LengthValidator
        );

        switch (count($lengthValidators)) {
            case 0:
                throw new RuntimeException(sprintf('Для поля %s валидация по длине не установлена', $field->getName()));
            case 1:
                self::reconfigureValidator(reset($lengthValidators), $lengthValidator);

                return $field;
            default:
                throw new RuntimeException(
                    sprintf(
                        'Для поля %s найдено несколько валидаторов длины, неоднозначно какой заменять',
                        $field->getName()
                    )
                );
        }
    }

    private static function reconfigureValidator(LengthValidator $oldValidator, LengthValidator $newValidator): void
    {
        $old = new ReflectionObject($oldValidator);
        [$min, $max] = [$old->getProperty('min'), $old->getProperty('max')];
        $min->setAccessible(true);
        $min->setValue($oldValidator, $newValidator->getMin());
        $min->setAccessible(false);

        $max->setAccessible(true);
        $max->setValue($oldValidator, $newValidator->getMax());
        $max->setAccessible(false);
    }
}
