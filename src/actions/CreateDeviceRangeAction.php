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
            '/([A-Z0]+)(\d+)-(\1)?(\d+)/',
            static function ($matches): string {
                $names = [];
                // Fixed the situation when the user has entered something like: DS9026-32
                if (($right = strlen($matches[4])) < ($left = strlen($matches[2]))) {
                    $matches[4] = substr($matches[2], 0, $left - $right) . $matches[4];
                }
                for ($i = min($matches[2], $matches[4]), $iMax = max($matches[2], $matches[4]); $i <= $iMax; ++$i) {
                    $names[] = $matches[1] . sprintf("%'.04d", $i);
                }

                return implode(',', $names);
            },
            $range
        );
    }
}
