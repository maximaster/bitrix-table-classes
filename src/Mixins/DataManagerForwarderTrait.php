<?php

declare(strict_types=1);

namespace Maximaster\BitrixTableClasses\Mixins;

use Bitrix\Main\Entity\DataManager;

trait DataManagerForwarderTrait
{
    /**
     * @psalm-param mixed ...$args
     * @psalm-return mixed
     */
    public static function forwardCallToDataManager(string $method, ...$args)
    {
        // @phpstan-ignore-next-line why:dynamic-typing
        return forward_static_call_array([DataManager::class, $method], $args);
    }
}
