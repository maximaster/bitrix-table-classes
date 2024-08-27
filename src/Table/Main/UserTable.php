<?php

declare(strict_types=1);

namespace Maximaster\BitrixTableClasses\Table\Main;

use Bitrix\Main\ORM\Fields\BooleanField;
use Bitrix\Main\ORM\Fields\Field;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\SystemException;
use Maximaster\BitrixEnums\Main\Truth;
use Maximaster\BitrixTableClasses\Mixins\Dmbat;
use Maximaster\BitrixTableClasses\Mixins\TableMapMergerTrait;
use Maximaster\BitrixTableFields\Field\NonEmptyStringField;
use Maximaster\BitrixTableFields\Validator\EmailValidator;

class UserTable extends \Bitrix\Main\UserTable
{
    use Dmbat;
    use TableMapMergerTrait;

    public const ID = 'ID';
    public const TIMESTAMP_X = 'TIMESTAMP_X';
    public const LOGIN = 'LOGIN';
    public const PASSWORD = 'PASSWORD';
    public const CHECKWORD = 'CHECKWORD';
    public const ACTIVE = 'ACTIVE';
    public const NAME = 'NAME';
    public const LAST_NAME = 'LAST_NAME';
    public const EMAIL = 'EMAIL';
    public const LAST_LOGIN = 'LAST_LOGIN';
    public const DATE_REGISTER = 'DATE_REGISTER';
    public const LID = 'LID';
    public const PERSONAL_PROFESSION = 'PERSONAL_PROFESSION';
    public const PERSONAL_WWW = 'PERSONAL_WWW';
    public const PERSONAL_ICQ = 'PERSONAL_ICQ';
    public const PERSONAL_GENDER = 'PERSONAL_GENDER';
    public const PERSONAL_BIRTHDATE = 'PERSONAL_BIRTHDATE';
    public const PERSONAL_PHOTO = 'PERSONAL_PHOTO';
    public const PERSONAL_PHONE = 'PERSONAL_PHONE';
    public const PERSONAL_FAX = 'PERSONAL_FAX';
    public const PERSONAL_MOBILE = 'PERSONAL_MOBILE';
    public const PERSONAL_PAGER = 'PERSONAL_PAGER';
    public const PERSONAL_STREET = 'PERSONAL_STREET';
    public const PERSONAL_MAILBOX = 'PERSONAL_MAILBOX';
    public const PERSONAL_CITY = 'PERSONAL_CITY';
    public const PERSONAL_STATE = 'PERSONAL_STATE';
    public const PERSONAL_ZIP = 'PERSONAL_ZIP';
    public const PERSONAL_COUNTRY = 'PERSONAL_COUNTRY';
    public const PERSONAL_NOTES = 'PERSONAL_NOTES';
    public const WORK_COMPANY = 'WORK_COMPANY';
    public const WORK_DEPARTMENT = 'WORK_DEPARTMENT';
    public const WORK_POSITION = 'WORK_POSITION';
    public const WORK_WWW = 'WORK_WWW';
    public const WORK_PHONE = 'WORK_PHONE';
    public const WORK_FAX = 'WORK_FAX';
    public const WORK_PAGER = 'WORK_PAGER';
    public const WORK_STREET = 'WORK_STREET';
    public const WORK_MAILBOX = 'WORK_MAILBOX';
    public const WORK_CITY = 'WORK_CITY';
    public const WORK_STATE = 'WORK_STATE';
    public const WORK_ZIP = 'WORK_ZIP';
    public const WORK_COUNTRY = 'WORK_COUNTRY';
    public const WORK_PROFILE = 'WORK_PROFILE';
    public const WORK_LOGO = 'WORK_LOGO';
    public const WORK_NOTES = 'WORK_NOTES';
    public const ADMIN_NOTES = 'ADMIN_NOTES';
    public const STORED_HASH = 'STORED_HASH';
    public const XML_ID = 'XML_ID';
    public const PERSONAL_BIRTHDAY = 'PERSONAL_BIRTHDAY';
    public const EXTERNAL_AUTH_ID = 'EXTERNAL_AUTH_ID';
    public const CHECKWORD_TIME = 'CHECKWORD_TIME';
    public const SECOND_NAME = 'SECOND_NAME';
    public const CONFIRM_CODE = 'CONFIRM_CODE';
    public const LOGIN_ATTEMPTS = 'LOGIN_ATTEMPTS';
    public const LAST_ACTIVITY_DATE = 'LAST_ACTIVITY_DATE';
    public const AUTO_TIME_ZONE = 'AUTO_TIME_ZONE';
    public const TIME_ZONE = 'TIME_ZONE';
    public const TIME_ZONE_OFFSET = 'TIME_ZONE_OFFSET';
    public const TITLE = 'TITLE';
    public const BX_USER_ID = 'BX_USER_ID';
    public const LANGUAGE_ID = 'LANGUAGE_ID';

    /** @var string */
    public const UF_ENTITY_ID = 'USER';

    /**
     * Метод возвращает идентификатор объекта, для которого запрашиваются пользовательские поля.
     */
    public static function getUfId(): string
    {
        return static::UF_ENTITY_ID;
    }

    /**
     * {@inheritDoc}
     *
     * @return Field[]|array[]
     *
     * @throws SystemException
     *
     * @psalm-return array<non-empty-string|int, array<non-empty-string, mixed>|Field>
     */
    public static function getMap(): array
    {
        return self::mergeMaps(parent::getMap(), [
            self::CHECKWORD => new StringField(self::CHECKWORD),
            self::ACTIVE => (new BooleanField(self::ACTIVE))
                ->configureValues(Truth::NO, Truth::YES)
                ->configureDefaultValue(Truth::YES),
            self::EMAIL => (new StringField(self::EMAIL))
                ->configureRequired(false)
                ->addValidator(EmailValidator::default()),
            self::LOGIN => NonEmptyStringField::required(self::LOGIN),
            self::NAME => new StringField(self::NAME),
            self::LAST_NAME => new StringField(self::LAST_NAME),
            self::SECOND_NAME => new StringField(self::SECOND_NAME),
        ]);
    }
}
