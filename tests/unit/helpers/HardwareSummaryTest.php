<?php declare(strict_types=1);

namespace hipanel\modules\server\tests\unit\helpers;

use PHPUnit\Framework\TestCase;
use hipanel\modules\server\helpers\HardwareSummary;
use hipanel\modules\server\helpers\HardwareType;

class HardwareSummaryTest extends TestCase
{
    public function canParseSummaryString(): void
    {
        $summaryString = 'SC826BE1C-R920LPB / X10DRI / 2xE5-2620V3 / 2x16GB DDR4 2133MHz ECC Reg / 4x4TB SATA (WD4002FYYZ) + 8x1.92TB SSD SATA (MZ-7LH1T9) @ LSI 9361-8i+BBU / 2xX710DA2';
        $summary = new HardwareSummary($summaryString);

        $this->assertEquals(
            'SC826BE1C-R920LPB / X10DRI / 2xE5-2620V3 / 2x16GB DDR4 2133MHz ECC Reg / 4x4TB SATA (WD4002FYYZ) + 8x1.92TB SSD SATA (MZ-7LH1T9) @ LSI 9361-8i+BBU / 2xX710DA2',
            $summary->getSummary()
        );
    }

    public function canSetPartByType(): void
    {
        $summaryString = 'SC826BE1C-R920LPB / X10DRI / 2xE5-2620V3 / 2x16GB DDR4 2133MHz ECC Reg / 4x4TB SATA (WD4002FYYZ) + 8x1.92TB SSD SATA (MZ-7LH1T9) @ LSI 9361-8i+BBU / 2xX710DA2';
        $summary = new HardwareSummary($summaryString);

        $summary->setPart(HardwareType::RAM, '4x32GB DDR4 3200MHz ECC Reg');
        $this->assertEquals(
            'SC826BE1C-R920LPB / X10DRI / 2xE5-2620V3 / 2x16GB DDR4 2133MHz ECC Reg / 4x4TB SATA (WD4002FYYZ) + 8x1.92TB SSD SATA (MZ-7LH1T9) @ LSI 9361-8i+BBU / 2xX710DA2',
            $summary->getSummary()
        );
    }
}
