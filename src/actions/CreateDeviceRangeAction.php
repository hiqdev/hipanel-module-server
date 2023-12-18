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
        $range = $this->expandRange($form->range);
        $loader = $this->payloadLoader->bindTo($this);
        $payload = $loader($range);

        return $this->controller->render('create', $payload);
    }

    private function expandRange(string $range): string
    {
        return preg_replace_callback(
            '/([A-Z]+)(\d+)-(\1)?(\d+)/',
            static function ($m): string {
                $names = [];
                // Fixed the situation when the user has entered something like: DS9026-32
                if (($right = strlen($m[4])) < ($left = strlen($m[2]))) {
                    $m[4] = substr($m[2], 0, $left - $right) . $m[4];
                }
                for ($i = min($m[2], $m[4]), $iMax = max($m[2], $m[4]); $i <= $iMax; ++$i) {
                    $names[] = $m[1] . sprintf("%'.04d", $i);
                }

                return implode(',', $names);
            },
            $range
        );
    }
}
