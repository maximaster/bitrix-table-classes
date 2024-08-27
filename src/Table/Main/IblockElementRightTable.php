<?php

declare(strict_types=1);

namespace Maximaster\BitrixTableClasses\Table\Main;

use Bitrix\Main\ArgumentException;
use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\ORM\Data\AddResult;
use Bitrix\Main\ORM\Data\DeleteResult;
use Bitrix\Main\ORM\Data\UpdateResult;
use Bitrix\Main\ORM\EntityError;
use Bitrix\Main\ORM\Fields\BooleanField;
use Bitrix\Main\ORM\Fields\Field;
use Bitrix\Main\ORM\Fields\IntegerField;
use Bitrix\Main\ORM\Fields\Relations\Reference;
use Bitrix\Main\ORM\Query\Join;
use Bitrix\Main\SystemException;
use Maximaster\BitrixEnums\Main\Truth;
use Maximaster\BitrixTableClasses\Table\Iblock\ElementTable;
use Maximaster\BitrixTableClasses\Table\Iblock\IBlockTable;
use Maximaster\BitrixTableClasses\Mixins\Dmbat;

/**
 * Описание таблицы с правами на элемент инфоблока.
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class IblockElementRightTable extends DataManager
{
    use Dmbat;

    public const IBLOCK_ID = 'IBLOCK_ID';
    public const SECTION_ID = 'SECTION_ID';
    public const ELEMENT_ID = 'ELEMENT_ID';
    public const RIGHT_ID = 'RIGHT_ID';
    public const IS_INHERITED = 'IS_INHERITED';

    // Ссылочные поля.
    public const RIGHT = 'RIGHT';
    public const ELEMENT = 'ELEMENT';
    public const IBLOCK = 'IBLOCK';

    /**
     * {@inheritDoc}
     */
    public static function getTableName(): string
    {
        return 'b_iblock_element_right';
    }

    /**
     * @return Field[]
     *
     * @throws ArgumentException
     * @throws SystemException
     *
     * @psalm-return array<string|int, Field|array<non-empty-string, mixed>>
     */
    public static function getMap(): array
    {
        return [
            self::IBLOCK_ID => (
                new IntegerField(self::IBLOCK_ID)
            )
                ->configureRequired(true),
            self::SECTION_ID => (
                new IntegerField(self::SECTION_ID)
            )
                ->configureRequired(true)
                ->configurePrimary(true),
            self::ELEMENT_ID => (
                new IntegerField(self::ELEMENT_ID)
            )
                ->configureRequired(true)
                ->configurePrimary(true),
            self::RIGHT_ID => (
                new IntegerField(self::RIGHT_ID)
            )
                ->configureRequired(true)
                ->configurePrimary(true),
            self::IS_INHERITED => (
                new BooleanField(self::IS_INHERITED)
            )
                ->configureRequired(true)
                ->configureValues(Truth::NO, Truth::YES),
            self::RIGHT => new Reference(
                self::RIGHT,
                IblockRightsTable::class,
                Join::on('this.' . self::RIGHT_ID, 'ref.' . IblockRightsTable::ID)
            ),
            self::ELEMENT => new Reference(
                self::ELEMENT,
                ElementTable::class,
                Join::on('this.' . self::ELEMENT_ID, 'ref.' . ElementTable::ID)
            ),
            self::IBLOCK => new Reference(
                self::IBLOCK,
                IBlockTable::class,
                Join::on('this.' . self::IBLOCK_ID, 'ref.' . IBlockTable::ID)
            ),
        ];
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
