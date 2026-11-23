<?php

declare(strict_types=1);

namespace hipanel\modules\server\actions;

use Closure;
use hipanel\actions\Action;
use hipanel\modules\server\forms\DeviceRangeForm;

class CreateDeviceRangeAction extends Action
{
    public Closure $payloadLoader;

    public function run(): string
    {
        $form = new DeviceRangeForm();
        $form->load($this->controller->request->post());
        $range = self::expandRange($form->range);
        $loader = $this->payloadLoader->bindTo($this);
        $payload = $loader($range);

        return $this->controller->render('create', $payload);
    }

    /**
     * Expands ranges like `DS9026-DS9032`, `DS9026-32` or `CO00018-CO00022` into a comma-separated list of names.
     * The numeric part keeps the width the user typed (at least 4 digits), so leading zeros are not doubled.
     */
    public static function expandRange(string $range): string
    {
        return preg_replace_callback(
            '/([A-Z]+)(\d+)-(?:\1)?(\d+)/',
            static function (array $matches): string {
                [, $prefix, $from, $to] = $matches;
                // Fixed the situation when the user has entered something like: DS9026-32
                if (($right = strlen($to)) < ($left = strlen($from))) {
                    $to = substr($from, 0, $left - $right) . $to;
                }
                $width = max(4, strlen($from));
                $names = [];
                for ($i = min((int)$from, (int)$to), $iMax = max((int)$from, (int)$to); $i <= $iMax; ++$i) {
                    $names[] = $prefix . str_pad((string)$i, $width, '0', STR_PAD_LEFT);
                }

                return implode(',', $names);
            },
            $range
        );
    }
}
