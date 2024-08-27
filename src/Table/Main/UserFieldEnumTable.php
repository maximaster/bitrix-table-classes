<?php

declare(strict_types=1);

namespace Maximaster\BitrixTableClasses\Table\Main;

use Bitrix\Main\ArgumentException;
use Bitrix\Main\DB\SqlQueryException;
use Bitrix\Main\ORM\Data\AddResult;
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Data\DeleteResult;
use Bitrix\Main\ORM\Data\UpdateResult;
use Bitrix\Main\ORM\EntityError;
use Bitrix\Main\ORM\Fields\Field;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Fields\StringField;
use Bitrix\Main\ORM\Fields\Validators\LengthValidator;
use Bitrix\Main\ORM\Query\Join;
use Bitrix\Main\SystemException;
use Maximaster\BitrixEnums\Main\Truth;
use Maximaster\BitrixTableClasses\Mixins\DataManagerForwarderTrait;
use Maximaster\BitrixTableClasses\Mixins\Dmbat;
use Maximaster\BitrixTableClasses\Mixins\TableMapMergerTrait;

/**
 * Таблица значений списка пользовательского поля.
 */
class UserFieldEnumTable extends DataManager
{
    use TableMapMergerTrait;
    use DataManagerForwarderTrait;
    use Dmbat;

    public const ID = 'ID';
    public const USER_FIELD_ID = 'USER_FIELD_ID';
    public const USER_FIELD = 'USER_FIELD';
    public const VALUE = 'VALUE';
    public const DEF = 'DEF';
    public const SORT = 'SORT';
    public const XML_ID = 'XML_ID';

    public static function getTableName(): string
    {
        return 'b_user_field_enum';
    }

    /**
     * @throws SystemException
     *
     * @psalm-return array<string|int, Field|array<non-empty-string, mixed>>
     */
    public static function getMap(): array
    {
        return [
            self::ID => (
                new IntegerField(self::ID)
            )
                ->configureAutocomplete(true)
                ->configurePrimary(true),
            self::USER_FIELD_ID => new IntegerField(self::USER_FIELD_ID),
            self::USER_FIELD => new Reference(
                self::USER_FIELD,
                UserFieldTable::class,
                Join::on('this.' . self::USER_FIELD_ID, 'ref.' . UserFieldTable::ID)
            ),
            self::VALUE => (
                new StringField(self::VALUE)
            )
                ->addValidator(new LengthValidator(1, 255)),
            self::DEF => (
                // TODO BooleanField?
                new StringField(self::DEF)
            )
                ->configureSize(1)
                ->configureDefaultValue(Truth::NO)
                ->addValidator(new LengthValidator(1, 1)),
            self::SORT => (
                new IntegerField(self::SORT)
            )
                ->configureDefaultValue(500),
            self::XML_ID => (
                new StringField(self::XML_ID)
            )
                ->addValidator(new LengthValidator(1, 255)),
        ];
    }

    /**
     * @throws ArgumentException
     * @throws SqlQueryException
     * @throws SystemException
     */
    public static function replaceFieldId(int $sourceFieldId, int $targetFieldId): void
    {
        $entity = static::getEntity();
        $connection = $entity->getConnection();
        $helper = $connection->getSqlHelper();
        $tableName = $entity->getDBTableName();

        $sql = 'UPDATE ' . $helper->quote($tableName)
            . ' SET ' . $helper->prepareAssignment($tableName, self::USER_FIELD_ID, strval($targetFieldId))
            . ' WHERE ' . $helper->prepareAssignment($tableName, self::USER_FIELD_ID, strval($sourceFieldId));

        $connection->queryExecute($sql);
        $entity->cleanCache();
    }

    /**
     * {@inheritDoc}
     *
     * @return AddResult
     *
     * @psalm-param array<non-empty-string, mixed> $data
     *
     * @phpstan-ignore-next-line why:dependency
     */
    public static function add(array $data)
    {
        $result = new AddResult();
        $result->addError(new EntityError('Добавление заблокировано.'));

        return $result;
    }

    /**
     * {@inheritDoc}
     *
     * @return UpdateResult
     *
     * @psalm-param array<non-empty-string, mixed> $data
     *
     * @phpstan-ignore-next-line why:dependency
     */
    public static function update($primary, array $data)
    {
        $result = new UpdateResult();
        $result->addError(new EntityError('Обновление заблокировано.'));

        return $result;
    }

    /**
     * {@inheritDoc}
     *
     * @return DeleteResult
     */
    public static function delete($primary)
    {
        $result = new DeleteResult();
        $result->addError(new EntityError('Удаление заблокировано.'));

        return $result;
    }
}
