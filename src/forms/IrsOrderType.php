<?php declare(strict_types=1);

namespace hipanel\modules\server\forms;

enum IrsOrderType: string
{
    case SETUP = 'setup';
    case DEDICATED = 'dedicated';
    case UNMANAGED = 'unmanaged';
}
