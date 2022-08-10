<?php

namespace hipanel\modules\server\tests\_support\Helper;

use Codeception\Module;

class OrderServerHelper extends Module
{
    protected array $requiredFields = ['server_order_allowed'];

    protected array $config = ['server_order_allowed' => '0'];

    public function canSeeOrderServer(): bool
    {
        return $this->config['server_order_allowed'] === '1';
    }

    public function getDisabledMessage(): string
    {
        return 'Order server is disabled in the vendor\'s asset settings';
    }
}
