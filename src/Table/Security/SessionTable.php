<?php

declare(strict_types=1);

namespace Maximaster\BitrixTableClasses\Table\Security;

use Bitrix\Security\SessionTable as BitrixSessionTable;
use Wikimedia\PhpSessionSerializer;

/**
 * Таблица хранения сессий.
 */
class SessionTable extends BitrixSessionTable
{
    public const SESSION_ID = 'SESSION_ID';
    public const SESSION_DATA = 'SESSION_DATA';

    /**
     * @psalm-param array<array-key, mixed> $filter
     *
     * @psalm-return iterable<non-empty-string, positive-int|null>
     */
    public static function iterateSessionUserId(array $filter): iterable
    {
        $rs = self::query()
            ->setSelect([static::SESSION_ID, static::SESSION_DATA])
            ->setFilter($filter)
            ->exec();

        while ($session = $rs->fetch()) {
            if ($session[static::SESSION_DATA] !== false) {
                $decodedSession = PhpSessionSerializer::decode(
                    (string) base64_decode($session[static::SESSION_DATA], true)
                );
                yield $session[static::SESSION_ID] => self::resolveSessionUsedId($decodedSession);
            }
        }
    }

    /**
     * @param array<array-key, mixed> $session
     *
     * @return positive-int|null
     */
    private static function resolveSessionUsedId(?array $session): ?int
    {
        if (
            $session === null
            || array_key_exists('SESS_AUTH', $session) === false
            || array_key_exists('USER_ID', $session['SESS_AUTH']) === false
        ) {
            return null;
        }

        $userId = intval($session['SESS_AUTH']['USER_ID']);

        return $userId > 0 ? $userId : null;
    }
}
