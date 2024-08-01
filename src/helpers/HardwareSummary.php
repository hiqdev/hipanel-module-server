<?php declare(strict_types=1);

namespace hipanel\modules\server\helpers;

class HardwareSummary
{
    private array $configParts;
    private array $configPatterns = [
        HardwareType::RAM->value => ['DDR4', 'MHz', 'GB'],
        HardwareType::HDD->value => ['HDD'],
        HardwareType::SSD->value => ['SSD', 'NVMe'],
        HardwareType::RAID->value => ['@'],
    ];

    public function __construct(string $summaryString)
    {
        $this->configParts = $this->parseSummary($summaryString);
    }

    public function __toString(): string
    {
        return $this->getSummary();
    }

    public function getSummary(): string
    {
        $summary = '';
        foreach ($this->configParts as $key => $value) {
            $summary .= ($key === HardwareType::RAID->value ? ' @ ' : ' / ') . $value;
        }

        return ltrim($summary, ' / ');
    }

    public function setPart(HardwareType $part, string $value): void
    {
        if (isset($this->configParts[$part->value])) {
            $this->configParts[$part->value] = preg_replace('/\s*\(.*?\)\s*/', '', $value);
        }
    }

    private function parseSummary(string $summaryString): array
    {
        $configParts = [];
        $summaryString = str_contains($summaryString, '@') ?
            substr_replace($summaryString, ' / @ ', strpos($summaryString, ' @ '), strlen(' @ ')) : $summaryString;
        foreach (explode(' / ', $summaryString) as $key => $part) {
            $keyType = null;
            foreach ($this->configPatterns as $type => $patterns) {
                foreach ($patterns as $pattern) {
                    if (str_contains($part, $pattern)) {
                        $keyType = $type;
                    }
                }
            }
            $configParts[$keyType ?? $key] = trim(ltrim($part, ' @'));
        }

        return $configParts;
    }
}
