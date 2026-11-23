<?php

declare(strict_types=1);

namespace hipanel\modules\server\tests\unit\actions;

use hipanel\modules\server\actions\CreateDeviceRangeAction;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class CreateDeviceRangeActionTest extends TestCase
{
    public static function rangeProvider(): array
    {
        return [
            'zero-padded prefix on both sides' => ['CO00018-CO00022', 'CO00018,CO00019,CO00020,CO00021,CO00022'],
            'zero-padded short right side' => ['CO00018-20', 'CO00018,CO00019,CO00020'],
            'four digits, full right side' => ['DS9026-DS9028', 'DS9026,DS9027,DS9028'],
            'four digits, short right side' => ['DS9026-28', 'DS9026,DS9027,DS9028'],
            'four digits with leading zeros' => ['DS0098-DS0101', 'DS0098,DS0099,DS0100,DS0101'],
            'short number is padded to four digits' => ['TEST1-3', 'TEST0001,TEST0002,TEST0003'],
            'reversed range' => ['DS0003-DS0001', 'DS0001,DS0002,DS0003'],
        ];
    }

    #[DataProvider('rangeProvider')]
    public function testExpandRange(string $range, string $expected): void
    {
        $this->assertSame($expected, CreateDeviceRangeAction::expandRange($range));
    }
}
