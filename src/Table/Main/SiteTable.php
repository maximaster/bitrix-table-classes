<?php

declare(strict_types=1);

namespace Maximaster\BitrixTableClasses\Table\Main;

use Bitrix\Main\SiteTable as BaseSiteTable;
use Maximaster\BitrixTableClasses\Mixins\Dmbat;

class SiteTable extends BaseSiteTable
{
    use Dmbat;

    public const LID = 'LID';
    public const SORT = 'SORT';
    public const DEF = 'DEF';
    public const ACTIVE = 'ACTIVE';
    public const NAME = 'NAME';
    public const DIR = 'DIR';
    public const LANGUAGE_ID = 'LANGUAGE_ID';
    public const DOC_ROOT = 'DOC_ROOT';
    public const DOMAIN_LIMITED = 'DOMAIN_LIMITED';
    public const SERVER_NAME = 'SERVER_NAME';
    public const SITE_NAME = 'SITE_NAME';
    public const EMAIL = 'EMAIL';
    public const CULTURE_ID = 'CULTURE_ID';
    public const CULTURE = 'CULTURE';
}
