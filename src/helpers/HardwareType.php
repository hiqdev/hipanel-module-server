<?php declare(strict_types=1);

namespace hipanel\modules\server\helpers;

enum HardwareType: string
{
    case RAM = 'ram';
    case HDD = 'hdd';
    case SSD = 'ssd';
    case RAID = 'raid';
}
