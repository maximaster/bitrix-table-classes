<?php

declare(strict_types=1);

namespace Maximaster\BitrixTableClasses\Table\Main;

use Bitrix\Main\ArgumentTypeException;
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields\Field;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\ORM\Fields\TextField;
use Bitrix\Main\ORM\Fields\Validators\LengthValidator;
use Bitrix\Main\SystemException;
use CUser;
use DateTimeImmutable;
use Maximaster\BitrixEnums\Main\EventLogSeverity;
use Maximaster\BitrixEnums\Main\ModuleId;
use Maximaster\BitrixTableFields\Field\DateTimeImmutableField;
use Maximaster\BitrixTableFields\Field\EnumObjectField;
use Maximaster\BitrixTableFields\Field\PrimaryIntegerField;

/**
 * Таблица журнала событий.
 */
class EventLogTable extends DataManager
{
    public const ID = 'ID';
    public const TIMESTAMP_X = 'TIMESTAMP_X';
    public const SEVERITY = 'SEVERITY';
    public const AUDIT_TYPE_ID = 'AUDIT_TYPE_ID';
    public const MODULE_ID = 'MODULE_ID';
    public const ITEM_ID = 'ITEM_ID';
    public const REMOTE_ADDR = 'REMOTE_ADDR';
    public const USER_AGENT = 'USER_AGENT';
    public const REQUEST_URI = 'REQUEST_URI';
    public const SITE_ID = 'SITE_ID';
    public const USER_ID = 'USER_ID';
    public const GUEST_ID = 'GUEST_ID';
    public const DESCRIPTION = 'DESCRIPTION';

    public static function getTableName(): string
    {
        return 'b_event_log';
    }

    /**
     * @return Field[]
     *
     * @throws ArgumentTypeException
     * @throws SystemException
     *
     * @psalm-return array<string|int, Field|array<non-empty-string, mixed>>
     *
     * @SuppressWarnings(PHPMD.CamelCaseVariableName) why:dependency
     */
    public static function getMap(): array
    {
        return [
            self::ID => PrimaryIntegerField::on(self::ID),
            self::TIMESTAMP_X => DateTimeImmutableField::on(self::TIMESTAMP_X)
                ->configureDefaultValue(static fn () => new DateTimeImmutable()),
            self::SEVERITY => EnumObjectField::on(self::SEVERITY, EventLogSeverity::class)
                ->configureRequired(true),
            self::AUDIT_TYPE_ID => (new StringField(self::AUDIT_TYPE_ID))
                ->configureRequired(true)
                ->addValidator(new LengthValidator(1, 50)),
            self::MODULE_ID => EnumObjectField::on(self::MODULE_ID, ModuleId::class)
                ->configureRequired(true),
            self::ITEM_ID => (new StringField(self::ITEM_ID))
                ->addValidator(new LengthValidator(1, 255)),
            self::REMOTE_ADDR => (new StringField(self::REMOTE_ADDR))
                ->configureRequired(true)
                ->addValidator(new LengthValidator(1, 40)),
            self::USER_AGENT => new TextField(self::USER_AGENT),
            self::REQUEST_URI => new TextField(self::REQUEST_URI),
            self::SITE_ID => (new StringField(self::SITE_ID))
                ->configureRequired(true)
                ->configureDefaultValue(static fn () => defined('SITE_ID') ? constant('SITE_ID') : ''),
            self::USER_ID => (new IntegerField(self::USER_ID))
                ->configureDefaultValue(static function (): ?int {
                    global $USER;

                    return $USER instanceof CUser ? (int) $USER->GetID() : null;
                }),
            self::GUEST_ID => new IntegerField(self::GUEST_ID),
            self::DESCRIPTION => new TextField(self::DESCRIPTION),
        ];
    }
}
