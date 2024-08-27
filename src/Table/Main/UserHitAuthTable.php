<?php

declare(strict_types=1);

namespace Maximaster\BitrixTableClasses\Table\Main;

use Bitrix\Main\ArgumentTypeException;
use Bitrix\Main\ORM\Data\AddResult;
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields\DatetimeField;
use Bitrix\Main\ORM\Fields\Field;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\ORM\Fields\Validators\LengthValidator;
use Bitrix\Main\SystemException;
use Bitrix\Main\Type\DateTime;
use Exception;
use Maximaster\BitrixTableFields\Field\PrimaryIntegerField;

/**
 * Таблица авторизации пользователя на хите привязанная к ссылке.
 */
class UserHitAuthTable extends DataManager
{
    public const ID = 'ID';
    public const USER_ID = 'USER_ID';
    public const HASH = 'HASH';
    public const URL = 'URL';
    public const SITE_ID = 'SITE_ID';
    public const TIMESTAMP_X = 'TIMESTAMP_X';

    public static function getTableName(): string
    {
        return 'b_user_hit_auth';
    }

    /**
     * @throws ArgumentTypeException
     * @throws SystemException
     *
     * @psalm-return array<string|int, Field|array<non-empty-string, mixed>>
     */
    public static function getMap(): array
    {
        return [
            self::ID => PrimaryIntegerField::on(self::ID),
            self::USER_ID => (new IntegerField(self::USER_ID))
                ->configureRequired(true),
            self::HASH => (new StringField(self::HASH))
                ->configureRequired(true)
                ->addValidator(new LengthValidator(32, 32)),
            self::URL => (new StringField(self::URL))
                ->configureRequired(true)
                ->addValidator(new LengthValidator(1, 255)),
            self::SITE_ID => (new StringField(self::SITE_ID))
                ->configureRequired(true)
                ->addValidator(new LengthValidator(1, 2)),
            self::TIMESTAMP_X => (new DatetimeField(self::TIMESTAMP_X))
                ->configureDefaultValue(static fn () => new DateTime()),
        ];
    }

    /**
     * Генерирует хеш совместимый который возможно сохранить в таблице.
     */
    public static function generateHash(): string
    {
        return md5(uniqid((string) rand(), true));
    }

    /**
     * Создаёт запись с указанным характером.
     *
     * @throws Exception
     */
    public static function create(string $hash, string $url, int $userId, string $siteId): AddResult
    {
        return self::add([
            self::USER_ID => $userId,
            self::HASH => $hash,
            self::URL => $url,
            self::SITE_ID => $siteId,
        ]);
    }
}
