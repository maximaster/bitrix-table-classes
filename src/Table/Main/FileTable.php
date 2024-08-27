<?php

declare(strict_types=1);

namespace Maximaster\BitrixTableClasses\Table\Main;

use Bitrix\Main\Entity\ExpressionField;
use Bitrix\Main\FileTable as BaseFileTable;
use Bitrix\Main\ORM\Data\UpdateResult;
use Bitrix\Main\ORM\Fields\Field;
use Bitrix\Main\SystemException;
use Exception;
use Maximaster\BitrixTableClasses\Mixins\Dmbat;
use Maximaster\BitrixTableClasses\Mixins\TableMapMergerTrait;

/**
 * Переопределяем базовый класс FileTable модуля "Main".
 * Переопределение делается для того, чтобы предоставить возможность создавать пользовательские поля для файлов.
 */
class FileTable extends BaseFileTable
{
    use TableMapMergerTrait;
    use Dmbat;

    public const ID = 'ID';
    public const TIMESTAMP_X = 'TIMESTAMP_X';
    public const MODULE_ID = 'MODULE_ID';
    public const HEIGHT = 'HEIGHT';
    public const WIDTH = 'WIDTH';
    public const FILE_SIZE = 'FILE_SIZE';
    public const CONTENT_TYPE = 'CONTENT_TYPE';
    public const SUBDIR = 'SUBDIR';
    public const FILE_NAME = 'FILE_NAME';
    public const ORIGINAL_NAME = 'ORIGINAL_NAME';
    public const DESCRIPTION = 'DESCRIPTION';
    public const HANDLER_ID = 'HANDLER_ID';
    public const EXTERNAL_ID = 'EXTERNAL_ID';
    public const PATH = 'PATH';

    /** @var string */
    public const UF_ENTITY_ID = 'MAIN_FILE';

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
            self::PATH => new ExpressionField(
                self::PATH,
                'CONCAT("/upload/", %s, "/", %s)',
                [self::SUBDIR, self::FILE_NAME]
            ),
        ]);
    }

    /**
     * Метод, который обновляет внешний код файла.
     *
     * @throws Exception
     */
    public static function updateExternalId($primary, string $externalId): UpdateResult
    {
        return parent::update($primary, [self::EXTERNAL_ID => $externalId]);
    }
}
